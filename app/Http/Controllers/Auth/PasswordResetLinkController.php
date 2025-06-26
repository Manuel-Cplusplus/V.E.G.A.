<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password', [
            'emailjs_service_id' => config('services.emailjs.service_id'),
            'emailjs_template_id' => config('services.emailjs.template_id'),
            'emailjs_user_id' => config('services.emailjs.user_id'),
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */


    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        // Crea token randomico
        $token = Str::random(64);

        // Memorizza in DB
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );


        // Costruisci il link di reset
        $resetLink = url("reset-password/{$token}") . '?email=' . urlencode($request->email);

        // Prepara i dati per l'email
        $templateParams = [
            'reset_link' => $resetLink,
            'email' => $request->email
        ];

        // Effettua la richiesta POST a EmailJS
        $response = Http::withHeaders([
            'origin' => url('/'),
        ])->post('https://api.emailjs.com/api/v1.0/email/send', [
            'service_id' => env('EMAILJS_SERVICE_ID'),
            'template_id' => env('EMAILJS_TEMPLATE_ID'),
            'user_id' => env('EMAILJS_USER_ID'),
            'template_params' => $templateParams
        ]);

        if (!$response->successful()) {
            return back()->withErrors(['email' => 'Errore nell\'invio dell\'email. Riprova più tardi.']);
        }

        return back()->with('success', 'Link per il reset della password inviato con successo!');
    }



    /*public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Attempt to send the reset password link
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            // Crea il link di reset
            $resetLink = url('password/reset/'.$request->email);

            // Metti il link nella sessione
            session()->put('reset_link', $resetLink);

            return back()->with('status', 'Link per il reset della password inviato con successo!');
        } else {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
        }
    }*/

}
