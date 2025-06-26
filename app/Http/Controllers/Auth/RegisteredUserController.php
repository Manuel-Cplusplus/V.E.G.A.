<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'gender' => ['nullable', 'in:Maschio,Femmina,Altro'],
            'role' => ['nullable', Rule::in(\App\Models\User::ROLES)],
            'NASA_API_KEY' => ['required'],
        ]);


        $user = User::create([
            'name' => $validatedData['name'],
            'surname' => $validatedData['surname'],
            'gender' => $validatedData['gender'] ?? null,
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => $validatedData['role'] ?? null,
            'NASA_API_KEY' => Crypt::encryptString($validatedData['NASA_API_KEY']), // Crittografa la chiave API
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->intended(route('homepage'));
    }
}
