
{{--
    V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
    Copyright (c) 2025 Manuel Carlucci

    Licensed under CC BY-NC-SA 4.0: http://creativecommons.org/licenses/by-nc-sa/4.0/
--}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('layouts.head')

<body class="antialiased min-h-screen">
<!-- antialiased: Migliora la leggibilità del testo sullo schermo -->
<!-- min-h-screen: Imposta l'altezza minima del corpo (<body>) uguale all'altezza dello schermo (viewport). -->

@include('layouts.header')


<div class="flex items-center justify-center mt-24">
    <main class="flex max-w-5xl rounded-2xl shadow-lg shadow-black overflow-hidden bg-grey-100">

        <!-- Sezione Login -->
        <div class="w-1/2 p-8 bg-gray-100">
            <h2 class="text-xl font-bold text-center italic">Log-In</h2>

            <form id="formConLoader" method="POST" action="{{ route('login') }}" class="mt-6">
                @csrf
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" class="block w-full mt-1 p-2 border rounded-md" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Inserisci la tua Email"/>
                    @error('email')
                    <span class="text-red-600 text-sm mt-2">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mt-4 relative">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" class="block w-full mt-1 p-2 border rounded-md" type="password" name="password" required autocomplete="current-password" placeholder="Inserisci la tua Password"/>
                    <span class="absolute top-8 right-3 cursor-pointer">
                    <i id="togglePassword" class="fa fa-eye"></i>
                </span>
                    @error('password')
                    <span class="text-red-600 text-sm mt-2">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between mt-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-gray-400">
                        <span class="ml-2 text-sm text-gray-700">Ricordami</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-blue-700 hover:underline">Password dimenticata?</a>
                    @endif
                </div>

                <!-- Pulsante Login -->
                <div class="mt-6">
                    <button type="submit" class="w-full bg-sky-800 text-white py-2 rounded-md font-semibold border-black border-2">Accedi</button>
                </div>
            </form>
        </div>

        <!-- Sezione Destra -->
        <div class="w-1/2 p-8 bg-gradient-to-b from-cyan-800 to-blue-500 text-white text-center shadow-[8px_0_15px_rgba(0,0,0,0.8),-8px_0_15px_rgba(0,0,0,0.8)]">
            <h2 class="text-lg font-semibold italic">Sei Nuovo?</h2>
            <p class="mt-2">Registrati e inizia questo viaggio con noi!</p>
            <a href="{{ route('register') }}" class="block mt-4 bg-yellow-500 text-black py-2 rounded-md font-bold border-black border-2">Registrati</a>

            <h2 class="text-lg font-semibold italic mt-10">Scopri i Vantaggi!</h2>
            <p class="mt-2">Vuoi vedere quali sono i vantaggi di registrarsi al nostro sistema?</p>
            <a href="{{ route('advantages') }}" class="block mt-4 bg-gray-200 text-black py-2 rounded-md font-bold border-black border-2">Vedi Vantaggi</a>
        </div>


        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    </main>
</div>

</body>


<!-- Loader -->
@include('components.loader')

{{-- @include('layouts.footer') --}}
</html>


<script>
    /* Toggle Password Visibility */
    document.addEventListener('DOMContentLoaded', () => {
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', () => {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // Alterna le classi per l'icona dell'occhio
            togglePassword.classList.toggle('fa-eye');
            togglePassword.classList.toggle('fa-eye-slash');
        });
    });
</script>
