{{--
    V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
    Copyright (c) 2025 Manuel Carlucci

    Licensed under CC BY-NC-SA 4.0: http://creativecommons.org/licenses/by-nc-sa/4.0/
--}}

<!-- Pulsante Indietro -->
{{--<div class="mt-4 flex justify-center">
    <button onclick="window.location.href='{{ route('asteroid.search') }}'"
            class="absolute top-56 left-64 bg-yellow-500 hover:bg-yellow-600 text-black px-6 py-3 rounded-2xl flex items-center font-semibold border-2 border-black">
        <!-- Freccia SVG -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
             viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5"></path>
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l-7-7 7-7"></path>
        </svg>
        Indietro
    </button>
</div>--}}

<div class="relative text-center mt-16 items-center">
    <!-- Immagine Asteroide -->
    <img src="{{ asset('media/icons/3D_asteroid.png') }}" alt="Asteroide"
         class="w-32 h-32 absolute -left-32 top-1/2 transform -translate-y-1/2">

    <!-- Nome Asteroide -->
    <h1 class="text-3xl font-bold text-white">
        {{ $asteroidData['name'] }} <br> [ID {{ $asteroidData['id'] }}]
    </h1>
</div>

<!-- Classe Oggetto: -->
<p class="absolute text-white text-xl top-72">Classe Orbitale:
    <span class="relative group cursor-pointer text-blue-400 underline"> {{ $orbitClass['orbit_class_type'] }}
            <span class="absolute transform -translate-x-24 hidden group-hover:block bg-gray-800 text-white text-[14px] rounded-2xl px-2 py-1 w-96 shadow-white shadow-offset-y-[-5px] shadow-2xl z-10">
                {{ $orbitClass['orbit_class_description'] }}<br>
                {{ $orbitClass['orbit_class_range'] }}
            </span>
        </span>
</p>

<!-- Preferiti -->
@if(auth()->user())
    @if(auth()->user()->favoriteAsteroids->contains('asteroid_id', $asteroidData['id']))
        <div class="text-center items-center flex flex-col">
            <form action="{{ route('favorites.remove') }}" method="POST">
                @csrf
                <input type="hidden" name="asteroid_id" value="{{ $asteroidData['id'] }}">
                <input type="hidden" name="asteroid_designation" value="{{ $asteroidData['name'] }}">
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
                <input type="hidden" name="asteroid_id" value="{{ $asteroidData['id'] }}">
                <input type="hidden" name="asteroid_designation" value="{{ $asteroidData['name'] }}">
                <button class="preferitiBtn absolute top-52 left-2/3 transform -translate-x-24" type="submit"
                        data-favorite="true">
                    <img src="{{ asset('media/icons/cuore_contorno_bianco.png') }}" alt="Preferiti" class="w-12 h-12"
                         title="Aggiungi ai Preferiti">
                </button>
            </form>
        </div>
    @endif
@else
    <button class="preferitiBtn absolute top-52 left-2/3 transform -translate-x-24" data-favorite="false"
            onclick="showPopup()">
        <img src="{{ asset('media/icons/cuore_contorno_bianco.png') }}" alt="Preferiti" class="w-12 h-12"
             title="Aggiungi ai Preferiti">
    </button>
@endif



<!-- Confronto Dimensioni -->
@include('tools.searchAsteroid.components.diameterComparison')


<!-- Contenitore principale -->
<div class="grid grid-cols-3 gap-1 mt-20">

    <!-- Informazioni Basilari -->
    <div class="bg-white opacity-75 px-6 py-4 rounded-2xl border-black border-2 shadow-black shadow-2xl w-4/5 ml-8 transform translate-x-4">
        <h2 class="font-extrabold text-[18px] underline text-center mb-2">Informazioni Basilari</h2>
        <ul class="text-left text">

            <li>
                <strong>Diametro calcolato:</strong>
                {{ !empty($asteroidData['diameter']) ? number_format($asteroidData['diameter'],2) . ' m' : 'Non disponibile' }}
                @if (!empty($asteroidData['diameter_uncertainty']))
                    ± {{ $asteroidData['diameter_uncertainty'] }} m
                @endif
            </li>

            <li>
                <strong>Distanza di approccio:</strong>
                @if(!empty($asteroidData['miss_distance_km']))
                    <span class="text-blue-700 underline cursor-pointer relative group"
                          onclick="showDistancePopup({{ $asteroidData['miss_distance_lunar'] ?? 'null' }})">
                          {{ $asteroidData['miss_distance_km'] }} km
                    <span class="absolute left-1/2 transform -translate-x-1/2 hidden group-hover:block bg-gray-800 text-white text-[14px] rounded-2xl px-2 py-1 w-auto shadow-white shadow-offset-y-[-5px] shadow-2xl z-10 mt-1">
                        Clicca qui per vedere una rappresentazione realistica della distanza
                    </span>
                </span>
                @else
                    Non disponibile
                @endif
            </li>


            <li>
                <strong>Velocità attuale:</strong>
                {{ !empty($asteroidData['velocity_km_s']) ? $asteroidData['velocity_km_s'] . ' km/s' : 'Non disponibile' }}
            </li>

            <li>
                <strong>Corpo Orbitante:</strong>
                {{ !empty($asteroidData['orbiting_body']) ? $asteroidData['orbiting_body'] : 'Non disponibile' }}
            </li>

            <li>
                <strong>Potenzialmente Pericoloso:</strong>
                @if (isset($asteroidData['is_hazardous']))
                    <span class="font-extrabold {{ $asteroidData['is_hazardous'] ? 'text-red-500' : 'text-green-500' }}">
                    {{ $asteroidData['is_hazardous'] ? 'Sì' : 'No' }}
                </span>
                @else
                    Non disponibile
                @endif
            </li>

            <li>
                <strong>Possibile Impatto Futuro:</strong>
                @if (isset($asteroidData['is_sentry_object']))
                    <span class="font-extrabold {{ $asteroidData['is_sentry_object'] ? 'text-red-500' : 'text-green-500' }}">
                    {{ $asteroidData['is_sentry_object'] ? 'Sì' : 'No' }}

                        {{--@if(auth()) --}}
                        @if ($asteroidData['is_sentry_object'])
                            >
                            <a href="{{ route('sentry.show', ['des' => $asteroidData['id']]) }}"
                               class="text-blue-600 underline">
                                   Dettagli
                                </a>
                        @endif
                        {{--@endif --}}

                    </span>
                @else
                    Non disponibile
                @endif
            </li>


            <li>
                <strong>Approfondimento:</strong>
                @if (!empty($asteroidData['link']))
                    <a class="text-blue-600 underline" href="{{ $asteroidData['link'] }}">Clicca qui</a>
                @else
                    Non disponibile
                @endif
            </li>
            <br>

        </ul>
    </div>


    <!-- Grafico Incontro Ravvicinato -->
    @include('tools.searchAsteroid.components.close_approachChart')


    <!-- Informazioni Tecniche -->
    <div class="bg-white opacity-75 px-6 py-4 rounded-2xl border-black border-2 shadow-black shadow-2xl w-96 ml-10 text-center">
        <h2 class="font-extrabold text-[18px] underline mb-2">Informazioni Tecniche</h2>

        @auth()
            <ul class="text-left">
                <li><strong>ID Orbita:</strong> {{ $technicalData['orbit_id'] }}</li>
                <li><strong>Orbita Determinata il:</strong> {{ $technicalData['orbit_determination_date'] }}</li>
                <li><strong>Prima Osservazione:</strong> {{ $technicalData['first_observation_date'] }}</li>
                <li><strong>Ultima Osservazione:</strong> {{ $technicalData['last_observation_date'] }}</li>
                <li><strong>Arco di dati (giorni):</strong> {{ $technicalData['data_arc_in_days'] }}</li>
                <li><strong>Osservazioni Usate:</strong> {{ $technicalData['observations_used'] }}</li>
                <br>

                <li>
                <span class="relative group cursor-pointer text-blue-500 underline"> Parametri Orbitali
                    <span class="absolute bottom-5 -left-6 hidden group-hover:block bg-gray-800 text-white text-[14px] rounded-2xl px-2 py-1 w-96 shadow-md shadow-white shadow-offset-y-[-5px] z-10">
                        <ul class="text-left">
                            <li><strong>Incertezza Orbitale:</strong> {{ $technicalData['orbit_uncertainty'] }}</li>
                            <li><strong>Intersezione Minima Orbita:</strong> {{ $technicalData['minimum_orbit_intersection'] }}</li>
                            <li><strong>Invariante di Tisserand con Giove:</strong> {{ $technicalData['jupiter_tisserand_invariant'] }}</li>
                            <li><strong>Epoca dell'Osculazione:</strong> {{ $technicalData['epoch_osculation'] }}</li>
                            <li><strong>Eccentricità:</strong> {{ $technicalData['eccentricity'] }}</li>
                            <li><strong>Magnitudine Assoluta:</strong> {{ $asteroidData['magnitude'] }}</li>
                            <li><strong>Semi-Asse Maggiore (AU):</strong> {{ $technicalData['semi_major_axis'] }}</li>
                            <li><strong>Inclinazione (°):</strong> {{ $technicalData['inclination'] }}</li>
                            <li><strong>Longitudine Nodo Ascendente (°):</strong> {{ $technicalData['ascending_node_longitude'] }}</li>
                        </ul>
                    </span>
                </span>
                </li>

                <li>
                <span class="relative group cursor-pointer text-blue-500 underline"> Dinamica Orbitale
                    <span class="absolute bottom-5 -left-6 hidden group-hover:block bg-gray-800 text-white text-[14px] rounded-2xl px-2 py-1 w-96 shadow-md shadow-white shadow-offset-y-[-5px] z-10">
                        <ul class="text-left">
                            <li><strong>Periodo Orbitale (giorni):</strong> {{ $technicalData['orbital_period'] }}</li>
                            <li><strong>Distanza Perielio (AU):</strong> {{ $technicalData['perihelion_distance'] }}</li>
                            <li><strong>Argomento del Perielio (°):</strong> {{ $technicalData['perihelion_argument'] }}</li>
                            <li><strong>Distanza Afelio (AU):</strong> {{ $technicalData['aphelion_distance'] }}</li>
                            <li><strong>Tempo Perielio:</strong> {{ $technicalData['perihelion_time'] }}</li>
                            <li><strong>Anomalia Media (°):</strong> {{ $technicalData['mean_anomaly'] }}</li>
                            <li><strong>Movimento Medio (°/giorno):</strong> {{ $technicalData['mean_motion'] }}</li>
                            <li><strong>Equinozio:</strong> {{ $technicalData['equinox'] }}</li>
                        </ul>
                    </span>
                </span>
                </li>
            </ul>
        @endauth
        @guest()
            <br><br><br><br>
            <p class="font-medium italic"> Devi Autenticarti per visualizzare i dati Tecnici. </p>
        @endguest
    </div>
</div>


<!-- Visualizzazione 3D -->
@include('tools.generalTools.distanceVisualization')

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



