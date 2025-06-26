
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


<div class="flex items-center justify-center mt-3">
    <main class="flex max-w-5xl rounded-2xl shadow-lg shadow-black overflow-hidden bg-grey-100">

        <!-- Sezione Registrazione -->
        <div class="px-8 py-2 bg-gray-100 items-center">
            <h2 class="text-2xl font-bold text-center italic mb-2">Registrazione</h2>
            <p class="text-[14px] text-gray-600">I dati contrassegnati con (*) sono obbligatori e necessari per l'identificazione univoca all'interno del sistema.</p>
            <p class="text-[14px] text-gray-600 mb-4">Nessuna informazione personale verrà condivisa con enti esterni, ad eccezione della tua API key, che sarà impiegata esclusivamente per le richieste ai database della NASA.</p>

            <form id="registrationForm" method="POST" action="{{ route('register') }}">
                @csrf

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nome *</label>
                        <input type="text" name="name" id="name" required class="w-full px-3 py-1.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Inserisci il tuo Nome">
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>
                    <div>
                        <label for="surname" class="block text-sm font-medium text-gray-700">Cognome *</label>
                        <input type="text" name="surname" id="surname" required class="w-full px-3 py-1.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Inserisci il tuo Cognome">
                        <x-input-error class="mt-2" :messages="$errors->get('surname')" />
                    </div>
                </div>

                <div class="mt-2">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                    <input type="email" name="email" id="email" required class="w-full px-3 py-1.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Inserisci la tua Email">
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div class="mt-2 relative">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password (min 8 caratteri) *</label>
                        <input type="password" name="password" id="password" required class="w-full px-3 py-1.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Inserisci la tua Password">
                        <span class="absolute top-7 right-3 cursor-pointer">
                            <i id="togglePassword" class="fa fa-eye"></i>
                        </span>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mt-2 relative">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Conferma Password *</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full px-3 py-1.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ripeti la tua Password">
                        <span class="absolute top-7 right-3 cursor-pointer">
                            <i id="togglePasswordConfirmation" class="fa fa-eye"></i>
                        </span>
                        <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                    </div>
                </div>

                <div class="mt-2">
                    <label for="api_key" class="block text-sm font-medium text-gray-700">API Key *</label>
                    <p class="text-xs text-gray-500 mb-1">Registrati su <a href="https://api.nasa.gov/" class="text-blue-500" target="_blank">https://api.nasa.gov/</a> ed inserisci la API Key che ti arriva per email.</p>
                    <input type="text" name="NASA_API_KEY" id="api_key" required class="w-full px-3 py-1.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Inserisci la tua API Key">
                    <x-input-error class="mt-2" :messages="$errors->get('NASA_API_KEY')" />
                </div>

                <div class="mt-2 flex justify-between">
                    <fieldset>
                        <legend class="text-sm font-medium text-gray-700">Genere</legend>
                        <div class="flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="gender" value="Maschio" class="form-radio">
                                <span class="ml-2">Maschio</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="gender" value="Femmina" class="form-radio">
                                <span class="ml-2">Femmina</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="gender" value="Altro" class="form-radio">
                                <span class="ml-2">Altro</span>
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                    </fieldset>

                    {{-- OLD
                    <fieldset>
                        <legend class="text-sm font-medium text-gray-700">Ruolo</legend>
                        <div class="flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="role" value="Appassionato" class="form-radio">
                                <span class="ml-2">Utente Appassionato</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="role" value="Ricercatore" class="form-radio">
                                <span class="ml-2">Ricercatore</span>
                            </label>
                        </div>
                    </fieldset>
                    --}}
                    <fieldset>
                        <div class="flex flex-row items-center justify-items-center gap-5 mt-2">
                            <label for="role" class="block text-sm font-medium text-gray-700">Ruolo</label>
                            <select id="role" name="role"
                                    class="w-full mt-1 px-5 py-1.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">-- Non selezionato -- </option>
                                @foreach(\App\Models\User::ROLES as $role)
                                    <option value="{{ $role }}" {{ old('role') === $role ? 'selected' : '' }}>
                                        {{ $role }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2"/>
                        </div>

                    </fieldset>
                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
                </div>

                <button type="submit" class="w-full bg-yellow-500 text-black py-2 rounded-md font-bold mt-4 flex justify-center border-black border-2">Registrati</button>
            </form>
        </div>


        <!-- Sezione Destra -->
        <div class="w-1/2 p-8 bg-gradient-to-b from-cyan-800 to-blue-500 text-white text-center shadow-[8px_0_15px_rgba(0,0,0,0.8),-8px_0_15px_rgba(0,0,0,0.8)]">
            <h2 class="text-lg font-semibold italic mt-20">Hai già un Account?</h2>
            <p class="mt-2">Accedi al nostro sistema per usufruire di vantaggi esclusivi.</p>
            <a href="{{ route('login') }}" class="block mt-4 bg-yellow-500 text-black py-2 rounded-md font-bold border-black border-2">Log In</a>

            <h2 class="text-lg font-semibold italic mt-10">Scopri i Vantaggi!</h2>
            <p class="mt-2">Vuoi vedere quali sono i vantaggi di registrarsi al nostro sistema?</p>
            <a href="{{ route('advantages') }}" class="block mt-4 bg-gray-200 text-black py-2 rounded-md font-bold border-black border-2">Vedi Vantaggi</a>
        </div>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    </main>
</div>

</body>

{{-- @include('layouts.footer') --}}
</html>



<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
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
