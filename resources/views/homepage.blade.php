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

    <!-- Sezione Strumenti -->
    <section class="mt-2 text-center">
        <h2 class="text-2xl font-bold text-green-400 flex items-center">
            <span class="flex-grow h-0.5 bg-white mr-2"></span>
            &#10022; Strumenti &#10022;
            <span class="flex-grow h-0.5 bg-white ml-2"></span>
        </h2>
        <p class="text-white text-l font-semibold mt-2">Posiziona il cursore su uno strumento per visualizzare maggiori
            dettagli.</p>

        <div class="grid grid-cols-6 gap-4 mt-4 text-white items-center">
            <button
                class="p-4 bg-gray-800 rounded-lg flex flex-col items-center hover:bg-gray-700 w-28 h-28 border-white border-2 relative"
                title="Questa sezione permette di usare tool generici come tool di conversione o di visualizzazione 3D."
                onclick="window.location.href='{{ route('generalTools') }}'">

                <div class="relative flex items-center justify-center">
                    <img src="media/icons/pianeta.png" alt="Impatti Futuri" class="w-12 h-12">
                    <img src="media/icons/righello.png" alt="Impatti Futuri"
                         class="w-8 h-8 absolute -top-1 left-1 transform -translate-x-6 -translate-y-1">
                </div>

                <span class="text-[14px]">Tool Generali</span>
            </button>

            <button
                class="p-4 bg-gray-800 rounded-lg flex flex-col items-center hover:bg-gray-700 w-28 h-28 border-white border-2"
                title="Questa funzione permette di cercare informazioni su un asteroide specifico o su una lista di asteroidi che rispettando determinate caratteristiche."
                onclick="window.location.href='{{ route('searchAsteroid') }}'">
                <img src="media/icons/ricerca_bianco.png" alt="Cerca Asteroide" class="w-10 h-10">
                <span class="mt-2 text-[14px]">Cerca Asteroide</span>
            </button>
            <button
                class="p-4 bg-gray-800 rounded-lg flex flex-col items-center hover:bg-gray-700 w-28 h-28 border-white border-2"
                title="Questa funzione permette di cercare incontri ravvicinati di asteroidi in un periodo selezionabile."
                onclick="window.location.href='{{ route('searchACloseApproaches') }}'">
                <img src="media/icons/calendario_terra.png" alt="Incontri Ravvicinati" class="w-10 h-10">
                <span class="mt-2 text-[14px]">Incontri Ravvicinati</span>
            </button>
            <button
                class="p-4 bg-gray-800 rounded-lg flex flex-col items-center hover:bg-gray-700 w-28 h-28 border-white border-2"
                title="Questa funzione permette di cercare eventi in cui meteore sono entrate nell’atmosfera terrestre a grande velocità, generando esplosioni luminose."
                onclick="window.location.href='{{ route('searchFireball') }}'">
                <img src="media/icons/impatto_asteroide.png" alt="Impatti Registrati" class="w-10 h-10">
                <span class="mt-2 text-[14px]">Impatti Atmosferici</span>
            </button>
            <button
                class="p-4 bg-gray-800 rounded-lg flex flex-col items-center hover:bg-gray-700 w-28 h-28 border-white border-2 relative"
                title="Questa funzione permette di cercare informazioni sui possibili impatti futuri degli asteroidi."
                onclick="window.location.href='{{ route('searchSentry') }}'">
                <div class="relative flex items-center justify-center">
                    <img src="media/icons/impatto_asteroide.png" alt="Impatti Futuri" class="w-10 h-10">
                    <img src="media/icons/punto_interrogativo.png" alt="Impatti Futuri"
                         class="w-8 h-8 absolute top-0 left-0 transform -translate-x-6 -translate-y-1">
                </div>
                <span class="mt-2 text-[14px]">Impatti Futuri</span>
            </button>
            {{--<button
                class="p-4 bg-gray-800 rounded-lg flex flex-col items-center hover:bg-gray-700 w-28 h-28 border-white border-2 relative"
                title="Questa funzione permette di effettuare Analisi Predittiva su Impatti Futuri prevedendo l'energia di irradiamento di un possibile impatto futuro basandosi su impatti registrati."
                onclick="window.location.href='{{ route('predictiveAnalysis') }}'">
                <div class="relative flex items-center justify-center">
                    <img src="media/icons/prediction.png" alt="Analisi Predittiva" class="w-10 h-10">
                </div>
                <span class="mt-2 text-[14px]">Analisi Predittiva</span>
            </button>--}}
            <button
                class="p-4 bg-gray-800 rounded-lg flex flex-col items-center hover:bg-gray-700 w-28 h-28 border-white border-2 relative"
                title="Questa funzione permette di effettuare il confronto di informazioni fra 2+ asteroidi."
                onclick="window.location.href='{{ route('compareAsteroids') }}'">
                <div class="relative flex items-center justify-center">
                    <img src="media/icons/confronto_nero.png" alt="Confronto Asteroidi" class="w-10 h-10">
                </div>
                <span class="mt-2 text-[14px]">Confronto Asteroidi</span>
            </button>

        </div>
    </section>

    <!-- Asteroidi con Impatto oggi -->
    <section class="relative w-screen max-w-screen-xl px-4 mt-2">
        <h2 class="text-2xl font-bold text-green-400 flex items-center">
            <span class="flex-grow h-0.5 bg-white mr-2"></span>
            &#10022; In Rilievo &#10022;
            <span class="flex-grow h-0.5 bg-white ml-2"></span>
        </h2>
        <p class="text-white text-l font-semibold mt-2 text-center">
            Asteroidi che si approcceranno alla Terra oggi:
            <span class="text-red-500 font-bold text-[18px] underline">{{ $AsteroidCount }}</span>.
        </p>


        <!-- Controllo Se Cont è 0 -->
        @if ($AsteroidCount == 0)
            <p class="text-white text-l font-semibold mt-2 text-center"> Oggi siamo abbastanza fortunati e non ci sono
                asteroidi in avvicinamento, cosa che di solito è rara, oppure hai raggiunto il limite massimo di
                richieste consentite. <br><br>
                Se non sei ancora registrato, iscriviti per aumentare il numero di richieste a tua disposizione. <br>
                Se sei già autenticato, ricorda che hai un limite di 1000 richieste all'ora, quindi attendi il reset del
                tuo
                limite.<br>
                Se hai dei dubbi sulla validità della tua API key controlla il tuo profilo.<br><br>
                Se hai ancora chiamate API disponibili e non vedi risultati, potrebbe esserci un problema con
                api.nasa.gov, ti chiediamo di aspettare.<br>
                Se il problema non si risolve, contatta: <a class="text-yellow-400     underline"
                                                            href="https://api.nasa.gov:443/contact/"> api.nasa.gov</a>
                per assistenza.</p>
        @else
            @if($AsteroidCount >= 5)
                <!-- Versione con SLIDER per 5 o più asteroidi -->
                <!-- Asteroide Precedente -->
                <div class="flex items-center">
                    <button id="prev"
                            class="bg-white bg-opacity-90 rounded-l-lg h-64 w-12 flex items-center justify-center border-black border-2">
                        <svg class="w-6 h-6 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>

                    <!-- Asteroidi -->
                    <div id="asteroid-slider" class="flex overflow-hidden w-screen space-x-4 p-4">
                        @foreach($asteroids as $asteroid)
                            <div
                                class="bg-white bg-opacity-90 px-4 py-4 rounded-lg text-black w-72 flex-shrink-0 border-black border-2 relative">

                                <!-- Preferiti -->
                                @if(auth()->user())
                                    @if(auth()->user()->favoriteAsteroids->contains('asteroid_id', $asteroid['id']))
                                        <form action="{{ route('favorites.remove') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="asteroid_id" value="{{ $asteroid['id'] }}">
                                            <input type="hidden" name="asteroid_designation"
                                                   value="{{ $asteroid['name'] }}">
                                            <button class="preferitiBtn absolute top-2 right-2" type="submit"
                                                    data-favorite="true">
                                                <img src="media/icons/cuore_pieno.png" alt="Preferiti" class="w-8 h-8"
                                                     title="Rimuovi dai Preferiti">
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('favorites.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="asteroid_id" value="{{ $asteroid['id'] }}">
                                            <input type="hidden" name="asteroid_designation"
                                                   value="{{ $asteroid['name'] }}">
                                            <button class="preferitiBtn absolute top-2 right-2" type="submit"
                                                    data-favorite="false">
                                                <img src="media/icons/cuore_contorno_nero.png" alt="Preferiti"
                                                     class="w-8 h-8" title="Aggiungi ai Preferiti">
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <button class="preferitiBtn absolute top-2 right-2" data-favorite="false"
                                            onclick="showPopup()">
                                        <img src="media/icons/cuore_contorno_nero.png" alt="Preferiti" class="w-8 h-8"
                                             title="Aggiungi ai Preferiti">
                                    </button>
                                @endif


                                <a href="{{ route('asteroid.show', ['id' => $asteroid['id']]) }}">
                                    <img src="{{ asset('media/icons/informazioni.png') }}" alt="Informazioni"
                                         class="w-8 h-8 absolute top-11 right-2 hover:scale-110 transition-transform duration-200">
                                </a>
                                <img src="media/icons/asteroide.png" alt="Asteroide"
                                     class="w-12 h-12 absolute top-4 left-3">

                                <h3 class="text-lg font-bold text-center">{{ $asteroid['name'] }}</h3>
                                <p class="text-center text-[16px] mb-1">[ID: {{ $asteroid['id'] }}]</p>
                                <p class="text-[14px]">Diametro: {{ number_format( $asteroid['diameter'], 2) }} m</p>

                                <p class="text-[14px]">
                                    Impatto evitato per:
                                    <span class="text-blue-700 underline cursor-pointer relative group"
                                          onclick="showDistancePopup({{ $asteroid['miss_distance_lunar'] }})">
                                        {{ number_format( $asteroid['miss_distance'] , 2) }} km
                                        <!-- Tooltip che appare al passaggio del mouse -->
                                        <span
                                            class="absolute left-1/2 transform -translate-x-1/2 hidden group-hover:block bg-gray-800 text-white text-[14px] rounded-2xl px-2 py-1 w-auto shadow-white shadow-offset-y-[-5px] shadow-2xl z-10">
                                            Clicca qui per vedere una rappresentazione realistica della distanza
                                        </span>
                                    </span>
                                </p>
                                <p class="text-[14px]">Velocità: {{ $asteroid['velocity'] }} km/s</p>
                                <p class="text-[14px]">Pericoloso:
                                    <span
                                        class="text-[16px] font-extrabold {{ $asteroid['hazardous'] ? 'text-red-500' : 'text-green-500' }}">
                                        {{ $asteroid['hazardous'] ? 'Sì' : 'No' }}
                                    </span>
                                </p>
                                <p class="text-[14px]">Possibile Impatto Futuro:
                                    <span
                                        class="text-[16px] font-extrabold {{ $asteroid['is_sentry_object'] ? 'text-red-500' : 'text-green-500' }}">{{ $asteroid['is_sentry_object'] ? 'Sì' : 'No' }}</span>
                                    @if ($asteroid['is_sentry_object'])
                                        >
                                        <a href="{{ route('sentry.show', ['des' => $asteroid['id']]) }}"
                                           class="text-[14px] text-blue-700 underline">
                                            Dettagli
                                        </a>
                                    @endif
                                </p>
                                <label class="block border-black border-2 rounded-lg mt-1">
                                    <div class="text-center font-bold">Live Countdown</div>
                                    <div data-impact-time="{{ $asteroid['impact_time'] }}" class="text-center text-[14px]">
                                        Calcolando...
                                    </div>
                                </label>

                            </div>
                        @endforeach
                    </div>

                    <!-- Prossimo Asteroide -->
                    <button id="next"
                            class="bg-white bg-opacity-90 rounded-r-lg h-64 w-12 flex items-center justify-center border-black border-2">
                        <svg class="w-6 h-6 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            @else
                <!-- Versione CENTRATA per meno di 5 asteroidi -->
                <div class="flex justify-center mt-4">
                    <div class="flex justify-center space-x-4 p-4">
                        @foreach($asteroids as $asteroid)
                            <div
                                class="bg-white bg-opacity-90 px-4 py-4 rounded-lg text-black w-72 border-black border-2 relative">

                                <!-- Preferiti -->
                                @if(auth()->user())
                                    @if(auth()->user()->favoriteAsteroids->contains('asteroid_id', $asteroid['id']))
                                        <form action="{{ route('favorites.remove') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="asteroid_id" value="{{ $asteroid['id'] }}">
                                            <input type="hidden" name="asteroid_designation"
                                                   value="{{ $asteroid['name'] }}">
                                            <button class="preferitiBtn absolute top-2 right-2" type="submit"
                                                    data-favorite="true">
                                                <img src="media/icons/cuore_pieno.png" alt="Preferiti" class="w-8 h-8"
                                                     title="Rimuovi dai Preferiti">
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('favorites.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="asteroid_id" value="{{ $asteroid['id'] }}">
                                            <input type="hidden" name="asteroid_designation"
                                                   value="{{ $asteroid['name'] }}">
                                            <button class="preferitiBtn absolute top-2 right-2" type="submit"
                                                    data-favorite="false">
                                                <img src="media/icons/cuore_contorno_nero.png" alt="Preferiti"
                                                     class="w-8 h-8" title="Aggiungi ai Preferiti">
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <button class="preferitiBtn absolute top-2 right-2" data-favorite="false"
                                            onclick="showPopup()">
                                        <img src="media/icons/cuore_contorno_nero.png" alt="Preferiti" class="w-8 h-8"
                                             title="Aggiungi ai Preferiti">
                                    </button>
                                @endif


                                <a href="{{ route('asteroid.show', ['id' => $asteroid['id']]) }}">
                                    <img src="{{ asset('media/icons/informazioni.png') }}" alt="Informazioni"
                                         class="w-8 h-8 absolute top-11 right-2 hover:scale-110 transition-transform duration-200">
                                </a>
                                <img src="media/icons/asteroide.png" alt="Asteroide"
                                     class="w-12 h-12 absolute top-4 left-3">

                                <h3 class="text-lg font-bold text-center">{{ $asteroid['name'] }}</h3>
                                <p class="text-center text-[16px] mb-1">[ID: {{ $asteroid['id'] }}]</p>
                                <p class="text-[14px]">Diametro: {{ number_format( $asteroid['diameter'], 2) }} m</p>

                                <p class="text-[14px]">
                                    Impatto evitato per:
                                    <span class="text-blue-700 underline cursor-pointer relative group"
                                          onclick="showDistancePopup({{ $asteroid['miss_distance_lunar'] }})">
                                    {{ number_format( $asteroid['miss_distance'] , 2) }} km
                                        <!-- Tooltip che appare al passaggio del mouse -->
                                    <span
                                        class="absolute left-1/2 transform -translate-x-1/2 hidden group-hover:block bg-gray-800 text-white text-[14px] rounded-2xl px-2 py-1 w-auto shadow-white shadow-offset-y-[-5px] shadow-2xl z-10">
                                        Clicca qui per vedere una rappresentazione realistica della distanza
                                    </span>
                                </span>
                                </p>
                                <p class="text-[14px]">Velocità: {{ $asteroid['velocity'] }} km/s</p>
                                <p class="text-[14px]">Pericoloso:
                                    <span
                                        class="text-[16px] font-extrabold {{ $asteroid['hazardous'] ? 'text-red-500' : 'text-green-500' }}">
                                    {{ $asteroid['hazardous'] ? 'Sì' : 'No' }}
                                </span>
                                </p>
                                <p class="text-[14px]">Possibile Impatto Futuro:
                                    <span
                                        class="text-[16px] font-extrabold {{ $asteroid['is_sentry_object'] ? 'text-red-500' : 'text-green-500' }}">{{ $asteroid['is_sentry_object'] ? 'Sì' : 'No' }}</span>
                                    @if ($asteroid['is_sentry_object'])
                                        >
                                        <a href="{{ route('sentry.show', ['des' => $asteroid['id']]) }}"
                                           class="text-[14px] text-blue-700 underline">
                                            Dettagli
                                        </a>
                                    @endif
                                </p>
                                <label class="block border-black border-2 rounded-lg mt-1">
                                    <div class="text-center font-bold">Live Countdown</div>
                                    <div data-impact-time="{{ $asteroid['impact_time'] }}" class="text-center text-[14px]">
                                        Calcolando...
                                    </div>
                                </label>

                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
    </section>


    <!-- Visualizzazione 3D -->
    @include('tools/generalTools.distanceVisualization')

    <!-- Popup Modal -->
    @include('components.popUp.login-popUp')
</main>

</body>
{{-- @include('layouts.footer') --}}
</html>


<script>

    /* Slider Asteroidi */
    document.addEventListener("DOMContentLoaded", function () {
        // Verifica se lo slider esiste (quindi se ci sono 5 o più asteroidi)
        const slider = document.getElementById("asteroid-slider");

        if (slider) {
            const prev = document.getElementById("prev");
            const next = document.getElementById("next");
            let scrollAmount = 0;
            const scrollStep = 500;

            /* Vai avanti nello sliding */
            next.addEventListener("click", function () {
                if (scrollAmount < slider.scrollWidth - slider.clientWidth) {
                    scrollAmount += scrollStep;
                    slider.scrollTo({left: scrollAmount, behavior: "smooth"});

                    updateButtonState();
                }
            });

            /* Vai indietro nello sliding */
            prev.addEventListener("click", function () {
                if (scrollAmount > 0) {
                    scrollAmount -= scrollStep;
                    slider.scrollTo({left: scrollAmount, behavior: "smooth"});

                    updateButtonState();
                }
            });

            /* Ingrigisci i bottoni next/prec se non ci sono altri asteroidi */
            function updateButtonState() {
                const canScrollLeft = scrollAmount > 0;
                const canScrollRight = scrollAmount < slider.scrollWidth - slider.clientWidth;

                prev.style.opacity = canScrollLeft ? "1" : "0.5";
                prev.style.cursor = canScrollLeft ? "pointer" : "not-allowed";
                prev.disabled = !canScrollLeft;

                next.style.opacity = canScrollRight ? "1" : "0.5";
                next.style.cursor = canScrollRight ? "pointer" : "not-allowed";
                next.disabled = !canScrollRight;
            }

            updateButtonState();
        }

    });

    /* Gestione Live Countdown */
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll("[data-impact-time]").forEach(element => {
            let impactTime = parseInt(element.getAttribute("data-impact-time"));
            if (!impactTime || isNaN(impactTime)) {
                element.innerHTML = "Dati non disponibili";
                return;
            }
            impactTime *= 1000; // Converti in millisecondi


            function updateCountdown() {
                let now = new Date().getTime();
                let distance = impactTime - now;

                if (distance < 0) {
                    element.innerHTML = "Passaggio Avvenuto";
                    return;
                }

                let days = Math.floor(distance / (1000 * 60 * 60 * 24));
                let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                let seconds = Math.floor((distance % (1000 * 60)) / 1000);

                element.innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
            }

            updateCountdown(); // Mostra subito il countdown senza aspettare 1 secondo
            setInterval(updateCountdown, 1000); // Aggiorna ogni secondo
        });
    });

</script>

