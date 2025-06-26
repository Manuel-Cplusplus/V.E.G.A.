{{--
    V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
    Copyright (c) 2025 Manuel Carlucci

    Licensed under CC BY-NC-SA 4.0: http://creativecommons.org/licenses/by-nc-sa/4.0/
--}}

<div id="comparison-container" class="-z-10 bg-white opacity-75 px-6 py-4 rounded-2xl border-black border-2 shadow-black shadow-2xl w-72 ml-16 text-center absolute top-36 right-4 transform -translate-x-36 h-48 max-h-48">
    <h2 class="font-medium text-[16px] mb-1">Dimensioni Comparabili a</h2>
    <p id="comparison-name" class="font-bold text-lg"></p>
    <div class="relative flex flex-col items-center mt-2">
        <img id="comparison-image" class="w-20 h-20" src="" alt="">
        <div class="absolute right-10 flex flex-col items-center text-sm">
            <span class="text-lg">↑</span>
            <div class="h-10 border-l-2 border-black"></div>
            <span class="text-lg">↓</span>
            <span class = "absolute left-3 top-9" id="comparison-size"></span>
        </div>

    </div>
</div>

<script src="{{ asset('js/asteroid-comparison.js') }}"></script>
<script>
    /*** Comparazione Dimensioni **/
    initAsteroidComparison({{ $asteroidData['diameter'] }});
</script>
