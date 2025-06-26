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


<div class="flex justify-center mt-4">
    <main class="flex w-full max-w-5xl rounded-2xl shadow-lg shadow-black overflow-hidden bg-grey-100">

        <div class="w-2/3 px-8 py-2 bg-gray-100">
            <h2 class="text-xl font-bold text-center italic mb-1 mt-2">Informazioni di Profilo</h2>

            <div class="mb-2 text-sm text-black text-center">
                <p> Aggiorna le informazioni del tuo profilo e il tuo indirizzo email. </p>
            </div>
            <form id="updateProfileForm" method="post" action="{{ route('profile.update') }}" class="mt-8">
                @csrf
                @method('patch')

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
                        <input type="text" name="name" id="name" required
                               class="w-full px-3 py-1.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               value="{{ auth()->user()->name }}">
                        <x-input-error class="mt-2" :messages="$errors->get('name')"/>

                    </div>
                    <div>
                        <label for="surname" class="block text-sm font-medium text-gray-700">Cognome</label>
                        <input type="text" name="surname" id="surname" required
                               class="w-full px-3 py-1.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               value="{{ auth()->user()->surname }}">
                        <x-input-error class="mt-2" :messages="$errors->get('surname')"/>
                    </div>
                </div>

                <div class="mt-2">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" required
                           class="w-full px-3 py-1.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ auth()->user()->email }}">
                    <x-input-error :messages="$errors->get('email')" class="mt-2"/>
                </div>

                <div class="mt-2">
                    <label for="api_key" class="block text-sm font-medium text-gray-700">API Key</label>
                    <input type="text" name="NASA_API_KEY" id="api_key" required
                           class="w-full px-3 py-1.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ Crypt::decryptString(auth()->user()->NASA_API_KEY) }}">
                    <x-input-error :messages="$errors->get('NASA_API_KEY')" class="mt-2"/>
                </div>

                <!-- Dati su Richieste disponibili con tale API -->
                @php
                    $percentage = ($totalRequests > 0) ? ($usedRequests / $totalRequests) * 100 : 0;
                @endphp

                <div class="mt-4">
                    <div class = "flex">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Utilizzo API</label>
                        <p class="text-xs text-gray-700 text-center items-center ml-64">
                            {{ $totalRequests - $usedRequests }} richieste disponibili su {{ $totalRequests }}
                        </p>
                    </div>

                    <div class="w-full bg-gray-300 rounded-full h-4">
                        <div class="bg-blue-600 h-4 rounded-full transition-all duration-300 ease-in-out"
                             style="width: {{ $percentage }}%">
                        </div>
                    </div>

                </div>


                <div class="grid grid-cols-2 gap-2 mt-2">
                    <!-- GENERE -->
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700">Genere</label>
                        <select id="gender" name="gender"
                                class="w-full px-3 py-1.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                            <option value="" disabled {{ is_null(auth()->user()->gender) ? 'selected' : '' }}>
                                -- Non selezionato --
                            </option>
                            <option value="Maschio" {{ auth()->user()->gender === 'Maschio' ? 'selected' : '' }}>
                                Maschio
                            </option>
                            <option value="Femmina" {{ auth()->user()->gender === 'Femmina' ? 'selected' : '' }}>
                                Femmina
                            </option>
                            <option value="Altro" {{ auth()->user()->gender === 'Altro' ? 'selected' : '' }}>
                                Altro
                            </option>
                        </select>
                        <x-input-error :messages="$errors->get('gender')" class="mt-2"/>
                    </div>

                    <!-- RUOLO -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Ruolo</label>
                        <select id="role" name="role"
                                class="w-full px-3 py-1.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Non selezionato --</option>
                            @foreach(\App\Models\User::ROLES as $role)
                                <option value="{{ $role }}" {{ auth()->user()->role === $role ? 'selected' : '' }}>
                                    {{ $role }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-2"/>
                    </div>

                </div>


                <div class="mt-6">
                    <button type="submit" class="w-full bg-sky-800 text-white py-2 rounded-md font-semibold">Salva
                    </button>
                </div>
            </form>

            @include('components.popUp.deleteAccount-popUp')
            <!-- Bottone per aprire il pop-up -->
            <button id="deleteAccountButton"
                    class=" mt-3 mb-3 w-full bg-red-700 text-white py-2 rounded-md font-semibold">
                Elimina Account
            </button>
        </div>


        <!-- Sezione Destra - psw -->
        <div class="w-1/2 p-8 bg-gradient-to-b from-cyan-800 to-blue-500 text-black text-center shadow-[8px_0_15px_rgba(0,0,0,0.8),-8px_0_15px_rgba(0,0,0,0.8)]">
            <h2 class="text-xl font-bold text-center italic mb-4 mt-4">Aggiorna la Password</h2>

            <div class="mb-4 text-sm text-black text-center">
                <p> Aggiorna la password del tuo profilo. </p>
            </div>

            <form id="updatePasswordForm" method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                @csrf
                @method('put')

                <!-- Campo Password Attuale -->
                <div class="relative">
                    <label for="current_password" class="block text-sm font-medium text-black">Password Attuale</label>
                    <input type="password" id="current_password" name="current_password"
                           class="w-full px-3 py-1.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10"
                           required>
                    <span class="absolute right-3 top-7 cursor-pointer text-gray-600" id="toggleOldPassword">
                        <i class="fas fa-eye"></i>
                    </span>
                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2"/>
                </div>

                <!-- Campo Nuova Password -->
                <div class="relative">
                    <label for="password" class="block text-sm font-medium text-black">Nuova Password</label>
                    <input type="password" id="password" name="password"
                           class="w-full px-3 py-1.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10"
                           required>
                    <span class="absolute right-3 top-7 cursor-pointer text-gray-600" id="toggleNewPassword">
                        <i class="fas fa-eye"></i>
                    </span>
                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2"/>
                </div>

                <!-- Campo Conferma Password -->
                <div class="relative">
                    <label for="password_confirmation" class="block text-sm font-medium text-black">Conferma
                        Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="w-full px-3 py-1.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10"
                           required>
                    <span class="absolute right-3 top-7 cursor-pointer text-gray-600"
                          id="toggleNewPasswordConfirmation">
                        <i class="fas fa-eye"></i>
                    </span>
                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2"/>
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full bg-sky-800 text-white py-2 rounded-md font-semibold">Salva
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

</body>

{{-- @include('layouts.footer') --}}
</html>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        function togglePasswordVisibility(toggleElement, passwordField) {
            toggleElement.addEventListener('click', function () {
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
        }

        togglePasswordVisibility(document.querySelector('#toggleOldPassword'), document.querySelector('#current_password'));
        togglePasswordVisibility(document.querySelector('#toggleNewPassword'), document.querySelector('#password'));
        togglePasswordVisibility(document.querySelector('#toggleNewPasswordConfirmation'), document.querySelector('#password_confirmation'));
    });
</script>

