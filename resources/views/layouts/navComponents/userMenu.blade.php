{{--
    V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
    Copyright (c) 2025 Manuel Carlucci

    Licensed under CC BY-NC-SA 4.0: http://creativecommons.org/licenses/by-nc-sa/4.0/
--}}

@auth
    <div class="relative group flex items-center mr-6">
        <button id="userDropdownBtn"
                class="text-medium px-4 py-2 rounded-md hover:bg-[#bef6] transition-all duration-200 flex items-center">
            {{ Auth::user()->name }} {{ Auth::user()->surname }}
            <svg class="w-4 h-4 ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div id="userDropdownMenu"
             class="absolute top-full left-0 w-48 bg-[#0D101F] shadow-lg rounded-md opacity-0 invisible transition-opacity duration-300">
            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-700">Profilo</a>
            <form method="POST" action="{{ route('logout') }}" class="block">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-700">Log Out</button>
            </form>
        </div>
    </div>
@else
    <a href="{{ route('login') }}"
       class="text-medium hover:bg-[#bef6] hover:rounded-md px-4 py-2 transition-all duration-200">Login/Registrazione</a>
@endauth



<script>
    /********* Dropdown Utente *********/
    const userDropdownBtn = document.getElementById("userDropdownBtn");
    const userDropdownMenu = document.getElementById("userDropdownMenu");
    let userHideTimeout;

    // Mostra la tendina quando il mouse entra nel bottone
    userDropdownBtn.addEventListener("mouseenter", () => {
        clearTimeout(userHideTimeout);
        userDropdownMenu.classList.remove("opacity-0", "invisible");
    });

    // Mantiene la tendina visibile quando il mouse è sopra il menu stesso
    userDropdownMenu.addEventListener("mouseenter", () => {
        clearTimeout(userHideTimeout);
    });

    // Nasconde la tendina con un piccolo ritardo solo quando il mouse lascia sia il bottone che il menu
    userDropdownBtn.addEventListener("mouseleave", () => {
        userHideTimeout = setTimeout(() => {
            userDropdownMenu.classList.add("opacity-0", "invisible");
        }, 300);
    });

    userDropdownMenu.addEventListener("mouseleave", () => {
        userHideTimeout = setTimeout(() => {
            userDropdownMenu.classList.add("opacity-0", "invisible");
        }, 300);
    });
</script>
