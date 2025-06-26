{{--
    V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
    Copyright (c) 2025 Manuel Carlucci

    Licensed under CC BY-NC-SA 4.0: http://creativecommons.org/licenses/by-nc-sa/4.0/
--}}

<div class="relative group flex items-center"> <!-- Flex per mantenere gli elementi sulla stessa riga -->
    <button id="dropdownBtn"
            class="text-medium px-4 py-2 rounded-md hover:bg-[#bef6] hover:rounded-md transition-all duration-200 flex items-center">
        Strumenti
        <!-- Icona freccetta -->
        <svg class="w-4 h-4 ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
             stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    <!-- Dropdown Strumenti -->
    <div id="dropdownMenu"
         class="absolute top-full left-0 w-48 bg-[#0D101F] shadow-lg rounded-md opacity-0 invisible transition-opacity duration-300 z-50">
        <a href="{{ route('generalTools') }}" class="block px-4 py-2 hover:bg-gray-700">Tool Generali</a>
        <a href="{{ route('searchAsteroid') }}" class="block px-4 py-2 hover:bg-gray-700">Cerca Asteroide</a>
        <a href="{{ route('searchACloseApproaches') }}" class="block px-4 py-2 hover:bg-gray-700">Incontri Ravvicinati</a>
        <a href="{{ route('searchFireball') }}" class="block px-4 py-2 hover:bg-gray-700">Impatti Atmosferici</a>
        <a href="{{ route('searchSentry') }}" class="block px-4 py-2 hover:bg-gray-700">Possibili Impatti Futuri</a>
        {{--<a href="{{ route('predictiveAnalysis') }}" class="block px-4 py-2 hover:bg-gray-700">Analisi Predittiva</a>--}}
        <a href="{{ route('compareAsteroids') }}" class="block px-4 py-2 hover:bg-gray-700">Confronto Asteroidi</a>
    </div>
</div>

<script>
    /********* Dropdown Strumenti *********/
    const dropdownBtn = document.getElementById("dropdownBtn");
    const dropdownMenu = document.getElementById("dropdownMenu");
    let hideTimeout;

    // Mostra la tendina quando il mouse è sopra il bottone
    dropdownBtn.addEventListener("mouseenter", () => {
        clearTimeout(hideTimeout);
        dropdownMenu.classList.remove("opacity-0", "invisible");
    });

    // Mantiene la tendina visibile quando il mouse è sopra
    dropdownMenu.addEventListener("mouseenter", () => {
        clearTimeout(hideTimeout);
    });

    // Nasconde la tendina dopo 300ms quando il mouse esce (elemento navbar)
    dropdownBtn.addEventListener("mouseleave", () => {
        hideTimeout = setTimeout(() => {
            dropdownMenu.classList.add("opacity-0", "invisible");
        }, 300);
    });

    // Nasconde la tendina dopo 300ms quando il mouse esce (elemento tendina)
    dropdownMenu.addEventListener("mouseleave", () => {
        hideTimeout = setTimeout(() => {
            dropdownMenu.classList.add("opacity-0", "invisible");
        }, 300);
    });
</script>
