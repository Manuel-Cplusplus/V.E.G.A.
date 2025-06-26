{{--
    V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
    Copyright (c) 2025 Manuel Carlucci

    Licensed under CC BY-NC-SA 4.0: http://creativecommons.org/licenses/by-nc-sa/4.0/
--}}

@if(auth()->user())
    @isset($neoWsAsteroid['id'])  <!-- Verifica se la chiave 'id' è definita -->
    @if(auth()->user()->favoriteAsteroids->contains('asteroid_id', $neoWsAsteroid['id']))
        <div class="text-center items-center flex flex-col">
            <form action="{{ route('favorites.remove') }}" method="POST">
                @csrf
                <input type="hidden" name="asteroid_id" value="{{ $neoWsAsteroid['id'] }}">
                <input type="hidden" name="asteroid_designation" value="{{ $neoWsAsteroid['name'] }}">
                <button class="preferitiBtn absolute top-52 left-2/3 transform -translate-x-24" type="submit"
                        data-favorite="true">
                    <img src="{{ asset('media/icons/cuore_pieno.png') }}" alt="Preferiti" class="w-12 h-12"
                         title="Rimuovi dai Preferiti">
                </button>
            </form>
        </div>
    @else
        <div class="text-center items-center flex flex-col">
            <form action="{{ route('favorites.add') }}" method="POST">
                @csrf
                <input type="hidden" name="asteroid_id" value="{{ $neoWsAsteroid['id'] }}">
                <input type="hidden" name="asteroid_designation" value="{{ $neoWsAsteroid['name'] }}">
                <button class="preferitiBtn absolute top-52 left-2/3 transform -translate-x-24" type="submit"
                        data-favorite="true">
                    <img src="{{ asset('media/icons/cuore_contorno_bianco.png') }}" alt="Preferiti"
                         class="w-12 h-12"
                         title="Aggiungi ai Preferiti">
                </button>
            </form>
        </div>
    @endif
    @endisset  <!-- Fine del controllo isset() -->
@else
    <button class="preferitiBtn absolute top-52 left-2/3 transform -translate-x-24" data-favorite="false"
            onclick="showPopup()">
        <img src="{{ asset('media/icons/cuore_contorno_bianco.png') }}" alt="Preferiti" class="w-12 h-12"
             title="Aggiungi ai Preferiti">
    </button>
@endif


<!-- Popup Modal -->
@include('components.popUp.login-popUp')


<script>
    /* Popup Preferiti */
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
