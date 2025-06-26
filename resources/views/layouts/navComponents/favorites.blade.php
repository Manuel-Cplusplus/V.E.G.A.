{{--
    V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
    Copyright (c) 2025 Manuel Carlucci

    Licensed under CC BY-NC-SA 4.0: http://creativecommons.org/licenses/by-nc-sa/4.0/
--}}

@php use Illuminate\Support\Facades\Auth; @endphp

@auth
    @php
        $countFavorites = Auth::check() ? Auth::user()->favoriteAsteroids->count() : 0;
        $favorites = Auth::user()->favoriteAsteroids->sortByDesc('created_at');
    @endphp
@endauth

<a href="{{ route('user.favorites') }}" id="preferitiBtn"
   class="hover:bg-[#bef6] hover:rounded-md p-1 transition-all duration-200 relative">
    <img src="{{ asset('media/icons/cuore_contorno_rosso.png') }}"
         title="Preferiti" alt="Preferiti" class="w-8 h-8">

    @auth
        @if($countFavorites > 0)
            <span
                class="absolute top-8 right-0 bg-red-600 text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center transform translate-x-1/2 -translate-y-1/2">
                {{ $countFavorites }}
            </span>
        @endif
    @endauth
</a>

<!-- Dropdown Preferiti -->
<div id="preferitiDropdownMenu"
     class="z-50 absolute top-full right-48 w-60 bg-[#0D101F] shadow-lg rounded-md opacity-0 invisible transition-opacity duration-300 max-h-60 overflow-y-auto scrollbar-thin scrollbar-thumb-white scrollbar-thumb-rounded scrollbar-track-[#0D101F]">

    @auth
        @if($favorites->count() > 0)
            @if($favorites->count() >= 4)
                <a href="{{ route('user.favorites') }}"
                   class="block px-4 py-2 font-bold text-blue-400 hover:text-white hover:bg-gray-700 border-b border-white">
                    Visualizza Tutti
                </a>
            @endif

            @foreach($favorites as $preferito)
                <a href="{{ route('asteroid.show', ['id' => $preferito->asteroid_id]) }}"
                   class="block px-4 py-2 hover:bg-gray-700 flex items-center border-b border-white last:border-0">
                    <img src="{{ asset('media/icons/asteroide.png') }}" alt="Asteroide" class="w-8 h-8 mr-3">
                    <span class="flex-1">{{ $preferito->asteroid_designation }} <br> [ID: {{ $preferito->asteroid_id }}]</span>
                    <svg class="w-4 h-4 transform -rotate-90" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 9l-7 7-7-7"></path>
                    </svg>
                </a>
            @endforeach
        @else
            <p class="block px-4 py-2 text-gray-500">Nessun asteroide nei preferiti.</p>
        @endif
    @endauth

    @guest
        <p class="block px-4 py-2 text-gray-500">Accedi per visualizzare i preferiti.</p>
    @endguest
</div>



<!-- CSS personalizzato per la scrollbar più sottile -->
<style>
    /* Personalizza la larghezza della scrollbar */
    #preferitiDropdownMenu::-webkit-scrollbar {
        width: 4px; /* Imposta la larghezza della scrollbar */
    }

    /* Colore e aspetto della parte della scrollbar che si muove */
    #preferitiDropdownMenu::-webkit-scrollbar-thumb {
        background-color: white;
        border-radius: 10px;
    }

    /* Colore della traccia della scrollbar */
    #preferitiDropdownMenu::-webkit-scrollbar-track {
        background-color: #0D101F;
    }
</style>


<script>
    /********* Gestione Preferiti *********/
    const preferitiBtn = document.getElementById("preferitiBtn");
    const preferitiDropdownMenu = document.getElementById("preferitiDropdownMenu");
    let preferitiHideTimeout;

    // Mostra la tendina quando il mouse entra nel bottone
    preferitiBtn.addEventListener("mouseenter", () => {
        clearTimeout(preferitiHideTimeout);
        preferitiDropdownMenu.classList.remove("opacity-0", "invisible");
        preferitiDropdownMenu.classList.add("opacity-100", "visible");
    });

    // Mantiene la tendina visibile quando il mouse è sopra il menu stesso
    preferitiDropdownMenu.addEventListener("mouseenter", () => {
        clearTimeout(preferitiHideTimeout);
    });

    // Nasconde la tendina con un piccolo ritardo solo quando il mouse lascia sia il bottone che il menu
    preferitiBtn.addEventListener("mouseleave", () => {
        preferitiHideTimeout = setTimeout(() => {
            preferitiDropdownMenu.classList.add("opacity-0", "invisible");
            preferitiDropdownMenu.classList.remove("opacity-100", "visible");
        }, 300);
    });

    preferitiDropdownMenu.addEventListener("mouseleave", () => {
        preferitiHideTimeout = setTimeout(() => {
            preferitiDropdownMenu.classList.add("opacity-0", "invisible");
            preferitiDropdownMenu.classList.remove("opacity-100", "visible");
        }, 300);
    });


    /*********** Popup Preferiti *********/
    const popupModal = document.getElementById("popupModal");
    const closePopupBtn = document.getElementById("closePopupBtn");


    function showPopup() {
        popupModal.classList.remove("opacity-0", "invisible");
        popupModal.classList.add("flex");
    }

    function hidePopup() {
        popupModal.classList.add("opacity-0", "invisible");
        setTimeout(() => popupModal.classList.remove("flex"), 300);
    }

    // Chiudi il popup quando l'utente clicca su "Chiudi"
    closePopupBtn.addEventListener("click", hidePopup);

</script>



