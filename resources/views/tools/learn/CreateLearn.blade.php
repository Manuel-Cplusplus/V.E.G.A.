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

<main class="flex flex-col items-center justify-center">

    <!-- Sezione Crea -->
    <section class="mt-2 text-center">
        <h2 class="text-2xl font-bold text-green-400 flex items-center">
            <span class="flex-grow h-0.5 bg-white mr-2"></span>
            &#10022; Crea Nuovo Contenuto &#10022;
            <span class="flex-grow h-0.5 bg-white ml-2"></span>
        </h2>
        <p class="text-white text-l font-semibold mt-2">Indica il titolo e una descrizione di cosa vuoi approfondire. <br>
            Il contenuto verrà generato automaticamente da Gemini AI e verrà inserito nell'elenco dei contenuti Learn.
            <br> In caso di problemi con la generazione del contenuto, tentare nuovamente o aspettare qualche minuto
            <br>Tieni presente che, come ogni modello linguistico, potrebbe commettere errori quindi assicurati di verificare le informazioni importanti.</p>

        <!-- Collegamento alla Lista Learn -->
        <div class="mt-4 text-center">
            <a href="{{route('LearnList')}}" class="text-yellow-300 hover:text-yellow-500 font-semibold transition duration-200">
                Vai alla Lista dei Contenuti Learn
            </a>
        </div>

        <!-- Form Creazione -->
        <form id="formConLoader" action="{{ route('learn.generate') }}" method="POST" class="w-full max-w-4xl mx-auto mt-4 text-white">
            @csrf

            <div class="mb-6">
                <label for="title" class="block text-left mb-1 font-semibold text-white">Titolo</label>
                <input type="text" id="title" name="title" placeholder="Inserisci il Titolo"
                       class="w-full p-3 rounded-xl text-black focus:outline-none focus:ring-2 focus:ring-yellow-400 shadow-md" />
            </div>

            <div class="mb-6">
                <label for="request" class="block text-left mb-1 font-semibold text-white">Richiesta</label>
                <textarea id="request" name="request" rows="4" placeholder="Inserisci il contenuto che vuoi imparare o approfondire."
                          class="w-full p-3 rounded-xl text-black focus:outline-none focus:ring-2 focus:ring-yellow-400 shadow-md resize-y overflow-auto"></textarea>
            </div>

            <div class="flex justify-center">
                <button type="submit"
                        class="px-6 py-2 bg-yellow-400 text-black font-bold rounded-xl hover:bg-orange-400 transition duration-300">
                    Genera contenuto
                </button>
            </div>
        </form>

    </section>



    <!-- Toast Notification -->
    @include('components.toastNotification')

    <!-- Loader -->
    @include('components.loader')

    <!-- Popup Modal -->
    @include('components.popUp.login-popUp')
</main>

</body>

{{-- @include('layouts.footer') --}}
</html>



