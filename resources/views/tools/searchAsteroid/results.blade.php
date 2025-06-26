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

    @if ($AsteroidCount == 0)
        <br><br><br><p class = "text-white text-2xl font-bold">Nessun risultato trovato per i filtri selezionati.</p><br>
        <p class = "text-white text-2xl font-bold">Prova a modificare i filtri di ricerca.</p><br>
        <p class = "text-white text-2xl font-bold text-center">Se il problema persiste, potresti aver superato il limite di chiamate <br>
            API disponibili. <br> In tal caso aspetti che si aggiorni il limite orario/giornaliero. </p>

    @else
        @if ($AsteroidCount == 1)
            @include('tools.searchAsteroid.components.single_result')
        @else
            @include('tools.searchAsteroid.components.multiple_results')
        @endif
    @endif

</main>

</body>

{{-- @include('layouts.footer') --}}
</html>


