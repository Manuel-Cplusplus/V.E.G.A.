{{--
    V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
    Copyright (c) 2025 Manuel Carlucci

    Licensed under CC BY-NC-SA 4.0: http://creativecommons.org/licenses/by-nc-sa/4.0/
--}}

<aside class="w-64 bg-white opacity-80 shadow-lg p-4">
    <nav class="space-y-2">
        <h2 class="text-lg font-semibold mb-4">Navigazione</h2>
        <ul class="space-y-1">

            <!-- Glossario -->
            <li id="menu-glossario" data-menu>
                <a href="{{ route('theory.glossario') }}" class="block px-3 py-2 rounded" data-link>Glossario</a>
            </li>

            <!-- Teoria -->
            <li>
                <button type="button" class="w-full flex items-center justify-between px-3 py-2 font-semibold text-gray-800 hover:text-blue-600 toggle-btn" data-target="teoria-sub">
                    <span>Teoria</span>
                    <svg class="w-4 h-4 transform transition-transform rotate-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <ul id="teoria-sub" class="ml-4 space-y-1">
                    <li id="menu-overview" data-menu><a href="{{ route('theory.teoria.overview') }}" class="block px-3 py-1 hover:text-blue-600" data-link>Overview Ricerca NEO</a></li>
                    <li id="menu-size" data-menu><a href="{{ route('theory.teoria.sizeEstimator') }}" class="block px-3 py-1 hover:text-blue-600" data-link>Estimatore di Dimensioni</a></li>
                    <li id="menu-meteoriti" data-menu><a href="https://it.geologyscience.com/geologia/diversit%C3%A0-mineralogica-delle-meteoriti/" target="_blank"  class="block px-3 py-1 hover:text-blue-600" data-link>Diversità mineralogica dei meteoriti</a></li>
                    <li id="menu-meteoriti_atlante" data-menu><a href="https://repositories.dst.unipi.it/index.php/meteoriti-in-sezione-sottile" target="_blank"  class="block px-3 py-1 hover:text-blue-600" data-link>Atlante dei meteoriti</a></li>
                </ul>
            </li>

            <!-- News -->
            <li id="menu-news" data-menu class="hover:text-blue-600">
                <a href="{{ route('theory.news') }}" class="block px-3 py-2" data-link>News</a>
            </li>

            <!-- Progetti -->
            <li>
                <button type="button" class="w-full flex items-center justify-between px-3 py-2 font-semibold text-gray-800 hover:text-blue-600 toggle-btn" data-target="progetti-sub">
                    <span>Progetti</span>
                    <svg class="w-4 h-4 transform transition-transform rotate-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <ul id="progetti-sub" class="ml-4 space-y-1 hover:text-blue-600">
                    <li id="menu-prisma" data-menu><a href="https://www.prisma.inaf.it/" class="block px-3 py-1" target="_blank" data-link>Progetto Prisma</a></li>
                </ul>
            </li>

            <!-- Difesa Planetaria -->
            <li>
                <button type="button" class="w-full flex items-center justify-between px-3 py-2 font-semibold text-gray-800 hover:text-blue-600 toggle-btn" data-target="difesa_planetaria-sub">
                    <span>Difesa Planetaria</span>
                    <svg class="w-4 h-4 transform transition-transform rotate-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <ul id="difesa_planetaria-sub" class="ml-4 space-y-1">
                    <li id="menu-difesa_intro" data-menu><a href="https://cneos.jpl.nasa.gov/pd/" target="_blank" class="block px-3 py-1 hover:text-blue-600" data-link>Informazioni Base</a></li>
                    <li id="menu-IAA" data-menu><a href="https://iaaspace.org/" target="_blank" class="block px-3 py-1 hover:text-blue-600" data-link>IAA - Eventi</a></li>
                    <li id="menu-palermo" data-menu><a href="https://iaaspace.org/event/9th-iaa-planetary-defense-conference-2025/" target="_blank" class="block px-3 py-1 hover:text-blue-600" data-link>Planetary Defense Conference 2025</a></li>
                </ul>
            </li>

        </ul>
    </nav>
</aside>


<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Toggling submenu
        document.querySelectorAll(".toggle-btn").forEach(btn => {
            const icon = btn.querySelector("svg");
            const targetId = btn.dataset.target;
            const subMenu = document.getElementById(targetId);

            btn.addEventListener("click", () => {
                subMenu.classList.toggle("hidden");
                icon.classList.toggle("rotate-90");
                icon.classList.toggle("rotate-0");
            });
        });

        // Evidenziazione attiva se c'è l'attributo data-selected
        document.querySelectorAll('[data-menu]').forEach(li => {
            if (li.hasAttribute('data-selected')) {
                const link = li.querySelector('[data-link]');
                link.classList.add('bg-blue-100', 'font-semibold', 'text-blue-800', 'rounded');
            }
        });
    });
</script>
