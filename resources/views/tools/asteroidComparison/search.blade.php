{{--
    V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
    Copyright (c) 2025 Manuel Carlucci

    Licensed under CC BY-NC-SA 4.0: http://creativecommons.org/licenses/by-nc-sa/4.0/
--}}

    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('layouts.head')

<body class="antialiased min-h-screen  text-white">

@include('layouts.header')

<main class="flex flex-col items-center justify-start px-4 py-2 space-y-6">

    <!-- Titolo e descrizione -->
    <section class="text-center">
        <h2 class="text-2xl font-bold text-green-400 flex items-center">
            <span class="flex-grow h-0.5 bg-white mr-2"></span>
            &#10022; Confronto Asteroidi &#10022;
            <span class="flex-grow h-0.5 bg-white ml-2"></span>
        </h2>

        <p class="text-sm sm:text-base leading-relaxed shadow-text-white">
            Questa sezione consente di confrontare dati basilari di più asteroidi. <br>
            Potrai Selezionare un asteroide fra quelli salvati fra i preferiti oppure cercarlo tramite denominazione.
        </p>
    </section>

    <div class="flex flex-row gap-10 items-center">
        <!-- Input di ricerca e selezione -->
        <section class="flex flex-col sm:flex-col items-center justify-center gap-4">
            <!-- Input ricerca -->
            <form id = "formConLoader" action="{{route('compareAsteroids.search')}}" method="POST">
                @csrf
                <div class="flex items-center bg-white text-black rounded-full px-3 shadow-md w-72">
                    <input name="search_query" type="search" placeholder="Denominazione" class="ml-2 w-full bg-transparent border-0" />
                    <span> | </span>
                    <button type="submit" title ="Clicca qui per cercare" class="ml-2 material-symbols-outlined">search</button>
                </div>
            </form>


            <!-- Oppure -->
            <span class="text-sm text-white font-semibold">oppure</span>

            <!-- Dropdown preferiti -->
            @if (auth()->check() && auth()->user()->favoriteAsteroids)
                <select name="favorite_des" class="bg-white text-black px-4 py-2 rounded shadow-md w-56" onchange="submitFavoriteDes(this)">
                    <option value="">Recupera dai Preferiti</option>
                    @foreach (auth()->user()->favoriteAsteroids as $fav)
                        <option value="{{ $fav->asteroid_designation }}">{{ $fav->asteroid_designation }}</option>
                    @endforeach
                </select>
            @endif


            <!-- Box Asteroide Selezionato -->
            @include('tools.asteroidComparison.components.selectedAsteroid')
        </section>


        <!-- Linea verticale divisoria -->
        <div class="h-96 border-l-2 border-white mx-4"></div>


        <!-- Tabella per gli asteroidi selezionati -->
        <div class="bg-white bg-opacity-95 text-black rounded-lg p-6 h-96 max-w-screen-md text-center items-center justify-center overflow-auto">
            <h1 class="text-center font-bold text-lg">Asteroidi Selezionati da confrontare:</h1>
            <p class="text-sm">Qui puoi avere una overview degli asteroidi che hai deciso di selezionare per il confronto.<br>
                Puoi anche deselezionarli se lo desideri. Puoi selezionare un minimo di 2 asteroidi ed un massimo di 5 asteroidi.</p>

            @include('tools.asteroidComparison.components.selectedTable')

        </div>

        </div>


    <!--  Errori -->
    @include('components.toastNotification')

    <!-- Loader -->
    @include('components.loader')

    <!-- Visualizzazione 3D -->
    @include('tools.generalTools.distanceVisualization')
</main>

{{-- Footer opzionale --}}
{{-- @include('layouts.footer') --}}

</body>
</html>


<script>
    /** Invio Form con favoriteAsteroids **/
    function submitFavoriteDes(selectElement) {
        let des = selectElement.value;

        if (des) {
            // Rimuove eventuali parentesi tonde
            // /[()]/ ->  Espressione regolare che cerca i caratteri ( o )
            // g -> "global", cioè sostituisce tutte le occorrenze trovate, non solo la prima.
            des = des.replace(/[()]/g, '').trim();

            const form = document.createElement('form');
            form.id = "formConLoader";
            form.method = 'POST';
            form.action = "{{ route('compareAsteroids.search')}}";

            const csrfToken = document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content');

            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = csrfToken;

            const desInput = document.createElement('input');
            desInput.type = 'hidden';
            desInput.name = 'des';
            desInput.value = des;

            form.appendChild(tokenInput);
            form.appendChild(desInput);

            document.body.appendChild(form);

            // Mostra il loading overlay
            document.getElementById('loadingOverlay').classList.add('flex');
            document.getElementById('loadingOverlay').classList.remove('hidden');

            form.submit();
        }
    }
</script>
