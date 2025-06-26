
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


<div class="flex justify-center mt-8">
    <main class="flex justify-center w-full max-w-4xl rounded-2xl shadow-lg shadow-black overflow-hidden bg-gray-100">

        <div class="w-1/2 p-8 mt-8">
            <h2 class="text-xl font-bold text-center italic mb-4">Reset Password</h2>
            <div class="mb-4 text-sm text-black text-center">
                <p> Hai dimenticato la password? <br>
                    Questa funzione ti permette di cambiare la password del tuo account. <br>
                    Non dimenticartela questa volta. </p>
            </div>

            <form id="updatePasswordForm" method="POST" action="{{ route('password.store') }}">
                @csrf

                <input type="hidden" id="token" name="token" value="{{ $request->route('token') }}">
                @error('token')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror

                <!-- Email -->
                {{--}}<div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                           class="w-full px-3 py-1.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>--}}
                <!-- Campo nascosto per l'email -->
                <input type="hidden" id="email" name="email">
                @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror

                <!-- Password -->
                <div class="mt-4 relative">
                    <label for="password" class="block text-sm font-medium text-gray-700">Nuova Password (min 8 caratteri)*</label>
                    <input type="password" id="password" name="password" required autocomplete="new-password"
                           class="w-full px-3 py-1.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10">
                    <span class="absolute top-7 right-3 cursor-pointer">
                        <i id="togglePassword" class="fa fa-eye"></i>
                    </span>
                    @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Conferma Password -->
                <div class="mt-4 relative">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Conferma Password *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                           class="w-full px-3 py-1.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10">
                    <span class="absolute top-7 right-3 cursor-pointer">
                        <i id="togglePasswordConfirmation" class="fa fa-eye"></i>
                    </span>
                    @error('password_confirmation')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end mt-10">
                    <button type="submit" class="bg-blue-600 text-white w-screen px-4 py-2 rounded-lg hover:bg-blue-700 transition border-black border-2">
                        Reimposta Password
                    </button>
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

<!-- Toast Notification -->
@include('components.toastNotification')

{{-- @include('layouts.footer') --}}
</html>



<script>
    document.addEventListener('DOMContentLoaded', () => {

        // Funzione per estrarre parametri dall'URL
        function getQueryParam(param) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
        }

        // Recupera email e token dall'URL e li inserisce nei rispettivi campi nascosti
        const email = getQueryParam('email');
        const token = window.location.pathname.split('/').pop(); // Prende l'ultima parte dell'URL

        if (email) {
            document.getElementById('email').value = decodeURIComponent(email);
        }
        if (token) {
            document.getElementById('token').value = token;
        }

        const togglePassword = document.querySelector('#togglePassword');
        const togglePasswordConfirmation = document.querySelector('#togglePasswordConfirmation');
        const password = document.querySelector('#password');
        const passwordConfirmation = document.querySelector('#password_confirmation');

        togglePassword.addEventListener('click', function(e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        togglePasswordConfirmation.addEventListener('click', function(e) {
            const type = passwordConfirmation.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirmation.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

    });


</script>
