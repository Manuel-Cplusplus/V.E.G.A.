
{{--
    V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
    Copyright (c) 2025 Manuel Carlucci

    Licensed under CC BY-NC-SA 4.0: http://creativecommons.org/licenses/by-nc-sa/4.0/
--}}


<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('layouts.head')

<body
    class="antialiased min-h-screen">

<!-- antialiased: Migliora la leggibilità del testo sullo schermo -->
<!-- min-h-screen: Imposta l'altezza minima del corpo (<body>) uguale all'altezza dello schermo (viewport). -->

@include('layouts.header')


<div class="flex justify-center mt-8">
    <main class="flex justify-center w-full max-w-4xl rounded-2xl shadow-lg shadow-black overflow-hidden bg-gray-100">

        <!-- Sezione Login -->
        <div class="w-1/2 p-8 mt-16">
            <h2 class="text-xl font-bold text-center italic mb-4">Password Dimenticata</h2>

            <div class="mb-4 text-sm text-black text-center">
                <p> Hai dimenticato la password? <br>
                    Nessun problema. Basta comunicarci il tuo indirizzo e-mail e ti invieremo un link per reimpostare la password.<br>
                    Il link ha una scadenza di 60 minuti. L'email ti giungerà dall'indirizzo: vega.astroproject@gmail.com. <br>
                    Assicurati di controllare la tua casella di posta elettronica, compresa la cartella spam, per il link di reimpostazione della password.</p>
            </div>

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="'Email'" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Pulsante Submit -->
                <div class="mt-6">
                    <button type="submit" class="w-full bg-sky-800 text-white py-2 rounded-md font-semibold border-black border-2">Richiedi Reset Link</button>
                </div>
            </form>

        </div>

        <!-- Sezione Destra -->
        <div class="w-1/2 p-8 bg-gradient-to-b from-cyan-800 to-blue-500 text-white text-center shadow-[8px_0_15px_rgba(0,0,0,0.8),-8px_0_15px_rgba(0,0,0,0.8)]">
            <h2 class="text-lg font-semibold italic">Sei Nuovo?</h2>
            <p class="mt-2">Registrati e inizia questo viaggio con noi!</p>
            <a href="{{ route('register') }}" class="block mt-4 bg-yellow-500 text-black py-2 rounded-md font-bold border-black border-2">Registrati</a>

            <h2 class="text-lg font-semibold italic mt-8">Hai già un Account?</h2>
            <p class="mt-2">Accedi al nostro sistema per usufruire di vantaggi esclusivi.</p>
            <a href="{{ route('login') }}" class="block mt-4 bg-yellow-500 text-black py-2 rounded-md font-bold border-black border-2">Log-In</a>

            <h2 class="text-lg font-semibold italic mt-8">Scopri i Vantaggi!</h2>
            <p class="mt-2 text-sm">Vuoi vedere quali sono i vantaggi di registrarsi al nostro sistema?</p>
            <a href="{{ route('advantages') }}" class="block mt-4 bg-gray-200 text-black py-2 rounded-md font-bold border-black border-2">Vedi Vantaggi</a>
        </div>
    </main>
</div>

</body>
<script src="https://cdn.emailjs.com/dist/email.min.js"></script>

<!-- Toast Notification -->
@include('components.toastNotification')

{{-- @include('layouts.footer') --}}
</html>



