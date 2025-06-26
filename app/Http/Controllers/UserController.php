<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\FavoriteAsteroid;
use App\Services\NasaApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;

/**
 * V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
 * Copyright (c) 2025 Manuel Carlucci
 *
 * This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/4.0/
 */

class UserController extends Controller
{
    /**
     * Get all the profile.
     */
    public function index($role = null)
    {
        $query = User::query();

        if ($role) {
            $query->where('role', '=', $role);
        } else {
            $query->where('role', '=', 'User');
        }

        $users = $query->get();

        return view('user.users', [
            'users' => $users,
            'role' => $role
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request, NasaApiService $nasaApiService): View
    {
        // Ottieni i dettagli dell'utente
        $user = $request->user();

        // Ottieni i dati sull'uso delle API (limiti e richieste rimanenti)
        $usage = $nasaApiService->getApiUsage();

        // Verifica se i dati sono stati ricevuti correttamente
        if ($usage) {
            $limit = $usage['limit'];
            $remaining = $usage['remaining'];
            $usedRequests = $limit - $remaining;
        } else {
            // Se i dati non sono stati ricevuti, imposta valori di fallback
            $limit = 1000;
            $remaining = 1000;
            $usedRequests = 0;
        }

        // Passa tutti i dati alla vista
        return view('profile.edit', [
            'user' => $user,
            'usedRequests' => $usedRequests,
            'totalRequests' => $limit,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        $user = $request->user();
        $user->name = $validatedData['name'];
        $user->surname = $validatedData['surname'];
        $user->email = $validatedData['email'];

        // Crittografa la chiave API prima di salvarla
        $user->NASA_API_KEY = isset($validatedData['NASA_API_KEY'])
            ? Crypt::encryptString($validatedData['NASA_API_KEY'])
            : $user->NASA_API_KEY;

        $user->gender = $validatedData['gender'] === 'Maschio' ? 'Male' :
            ($validatedData['gender'] === 'Femmina' ? 'Female' : 'Other');

        $user->role = $validatedData['role'];

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }


    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string',
            'password_confirmation' => 'required|string|same:password',
        ]);

        $user = $request->user();

        // Controllo se la password attuale è corretta
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return Redirect::route('profile.edit')->withErrors(['current_password' => 'La password attuale non è corretta.']);
        }

        // Aggiorna la password
        $user->update([
            'password' => Hash::make($request->input('password'))
        ]);

        return Redirect::route('homepage')->with('status', 'password-updated');
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }



    // Aggiungi ai preferiti
    public function addToFavorites(Request $request)
    {
        $validated = $request->validate([
            'asteroid_id' => 'nullable|string',
            'asteroid_designation' => 'nullable|string',
        ]);

        $asteroidId = $validated['asteroid_id'];
        $designation = $validated['asteroid_designation'];

        $nasaService = app(NasaApiService::class);
        $neoWsAsteroid = $nasaService->fetchData('NeoWS_ID', ['id' => $asteroidId]);

        $cadDate = null;
        $isSentry = false;
        $impactProbability = null;
        $impactDate = null;
        $torinoScale = null;

        // Recupera la data del primo approccio futuro successivo alla data odierna
        if (!empty($neoWsAsteroid['close_approach_data'])) {
            $today = Carbon::today();

            $closestFutureCad = collect($neoWsAsteroid['close_approach_data'])
                ->filter(function ($approach) use ($today) {
                    return isset($approach['close_approach_date']) &&
                        Carbon::parse($approach['close_approach_date'])->greaterThanOrEqualTo($today);
                })
                ->sortBy('close_approach_date')
                ->first();

            $cadDate = $closestFutureCad['close_approach_date'] ?? null;
        }

        // Determina se è un oggetto monitorato da Sentry
        $isSentry = isset($neoWsAsteroid['is_sentry_object']) ? $neoWsAsteroid['is_sentry_object'] : false;

        // Se è un oggetto Sentry, recupera i dati specifici
        if ($isSentry && $asteroidId) {
            $sentryResults = $nasaService->fetchData('Sentry', ['des' => $asteroidId]);

            //dd($sentryResults);

            // recupera le informazioni di impatto della data più vicina alla data odierna
            if (isset($sentryResults['data']) && is_array($sentryResults['data'])) {
                $now = Carbon::now();

                $latest = collect($sentryResults['data'])
                    ->filter(function ($item) use ($now) {
                        // Pulisce la data rimuovendo la parte decimale e la confronta
                        $dateOnly = explode('.', $item['date'])[0];
                        return Carbon::parse($dateOnly)->greaterThan($now);
                    })
                    ->sortBy(function ($item) {
                        return Carbon::parse(explode('.', $item['date'])[0]);
                    })
                    ->first();

                if ($latest) {
                    $impactProbability = isset($latest['ip']) ? (float) $latest['ip'] : null;

                    $impactDate = \Carbon\Carbon::parse(explode('.', $latest['date'])[0])->toDateString();

                    $torinoScale = isset($latest['ts']) ? (int) $latest['ts'] : null;
                }
            }

        }

        //dd($impactProbability, $impactDate, $torinoScale);

        // Aggiungi o aggiorna l'asteroide nei preferiti
        FavoriteAsteroid::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'asteroid_id' => $asteroidId,
                'asteroid_designation' => $designation,
            ],
            [
                'cad' => $cadDate,
                'isSentry' => $isSentry,
                'impact_probability' => $impactProbability,
                'impact_date' => $impactDate,
                'torino_scale' => $torinoScale,
            ]
        );

        // Controlla se la pagina precedente è "search"
        $previousUrl = url()->previous();
        if (str_contains($previousUrl, '/search')) {
            return redirect()->route('searchResults')->with('status', 'Aggiunto ai preferiti!');
        }

        return redirect()->back()->with('status', 'Aggiunto ai preferiti!');
    }


    public function removeFromFavorites(Request $request)
    {
        $validated = $request->validate([
            'asteroid_id' => 'nullable|string',
            'asteroid_designation' => 'nullable|string',
        ]);

        $query = FavoriteAsteroid::where('user_id', auth()->id());

        if (!empty($validated['asteroid_id'])) {
            $query->where('asteroid_id', $validated['asteroid_id']);
        }

        if (!empty($validated['asteroid_designation'])) {
            $query->orWhere('asteroid_designation', $validated['asteroid_designation']);
        }

        $query->delete();

        // Controlla se la pagina precedente è "search"
        $previousUrl = url()->previous();
        if (str_contains($previousUrl, '/search')) {
            return redirect()->route('searchResults')->with('status', 'Rimosso dai preferiti!');
        }

        return redirect()->back()->with('status', 'Rimosso dai preferiti!');
    }


    // Elenco degli asteroidi preferiti
    public function listAllFavorites()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Utente non autenticato'], 401);
        }

        $favorites = $user->favoriteAsteroids()
            ->orderByDesc('id') // Ordina per ID decrescente (più recente prima)
            ->get();

        // return response()->json($favorites);
        return view('user.favoriteAsteroids', ['asteroids' => $favorites]);

    }


}


