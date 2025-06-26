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

    <!-- Img 3d Asteroide -->
    <div class="relative text-center mt-16 items-center">
        <!-- Immagine Asteroide -->
        <img src="{{ asset('media/icons/3D_asteroid.png') }}" alt="Asteroide"
             class="w-32 h-32 absolute -left-32 top-1/2 transform -translate-y-1/2">

        <!-- Nome Asteroide -->
        <h1 class="text-3xl font-bold text-white">
            {{ $sentrySummary['fullname'] }} <br>
            @isset($neoWsAsteroid['id'])
                [ID {{ $neoWsAsteroid['id'] }}]
            @else
                [ID Non Disponibile]
            @endisset
        </h1>

    </div>

    <!-- Classe Oggetto: -->
    <p class="absolute text-white text-xl top-72">Classe Orbitale:

        <span class="relative group cursor-pointer text-blue-400 underline">
        @isset($neoWsAsteroid['orbital_data']['orbit_class']['orbit_class_type'])
                {{ $neoWsAsteroid['orbital_data']['orbit_class']['orbit_class_type'] }}
            @else
                N/A
            @endisset

        <span class="absolute transform -translate-x-24 hidden group-hover:block bg-gray-800 text-white text-[14px] rounded-2xl px-2 py-1 w-96 shadow-white shadow-offset-y-[-5px] shadow-2xl z-10">
            @isset($neoWsAsteroid['orbital_data']['orbit_class']['orbit_class_description'])
                {{ $neoWsAsteroid['orbital_data']['orbit_class']['orbit_class_description'] }}
            @else
                Descrizione non disponibile
            @endisset

            <br>

            @isset($neoWsAsteroid['orbital_data']['orbit_class']['orbit_class_range'])
                {{ $neoWsAsteroid['orbital_data']['orbit_class']['orbit_class_range'] }}
            @endisset
        </span>
    </span>

    </p>


    <!-- Comparazione Energia -->
    @include('tools.searchSentry.components.energyComparison')

    <!-- Preferiti -->
    @include('tools.searchSentry.components.favoritesHandling')

    <!-- Comparazione Diametro -->
    @include('tools.searchSentry.components.diameterComparison')

    <!-- Informazioni Base -->
    <div class="grid grid-cols-3 gap-1 mt-24">

        <!-- Informazioni Basilari -->
        <div class="bg-white opacity-75 px-6 py-4 rounded-2xl border-black border-2 shadow-black shadow-2xl w-fit min-w-96 max-w-sm">
            <h2 class="font-extrabold text-[18px] underline text-center">Informazioni Basilari</h2>
            <p class="mb-4 text-center italic text-sm"> (Ponderati in base alle probabilità di impatto) </p>
            <ul class="text-left">
                <li>
                    <strong title="La probabilità cumulativa che l'impatto avvenga. Il calcolo è complesso e può variare anche di un ordine di grandezza."
                            class="cursor-help px-1 rounded">
                        Probabilità di Impatto Stimata:
                    </strong>
                    <span class="underline text-blue-600 cursor-pointer"
                          title="{{ number_format($sentrySummary['ip'], 10) }} %">
                          1 su {{ number_format(1 / $sentrySummary['ip'], 0, ',', '.') }}
                    </span>
                </li>

                <li>
                    <strong title="Energia cinetica al momento dell'impatto: 0.5 × massa × velocità², misurata in Megatoni di TNT."
                            class="cursor-help px-1 rounded">
                        Energia di Impatto Stimata:
                    </strong>
                    {{ $sentrySummary['energy'] * 1000 }} kT
                </li>

                <li class="mt-2">
                    <strong title="Massa stimata considerando un corpo sferico uniforme con densità di 2.6 g/cm³. È solo una stima approssimativa."
                            class="cursor-help px-1 rounded">
                        Massa Stimata:
                    </strong>
                    {{ number_format($sentrySummary['mass'], 2) }} kg
                </li>

                <li class="mb-2">
                    <strong title="Diametro stimato sulla base della magnitudine assoluta, assumendo un corpo sferico con albedo visiva di 0.154."
                            class="cursor-help px-1 rounded">
                        Diametro Stimato:
                    </strong>
                    {{ number_format($sentrySummary['diameter'] * 1000, 2) }} m
                    <p class="mt-0.5 italic text-[12px] ml-5">
                        Questo valore potrebbe differire dal valore ricavato nella ricerca da precedenti misurazioni.
                        <span title="Questo è dovuto all'utilizzo di tecniche differenti nel recupero di queste informazioni. I diametri degli asteroidi sono calcolati usando metodi diversi: alcuni si basano sulla magnitudine e albedo, altri su dati radar o termici. Inoltre, le osservazioni possono essere state fatte in momenti diversi con strumenti di precisione variabile."
                              class="cursor-help text-blue-600 underline">Perché?</span>
                    </p>
                </li>


                <li>
                    <strong title="Velocità al momento dell'ingresso atmosferico."
                            class="cursor-help px-1 rounded">
                        Velocità di Impatto:
                    </strong>
                    {{ $sentrySummary['v_imp'] }} km/s
                </li>
                @auth()
                    <li>
                        <strong title="Velocità relativa all'ingresso atmosferico trascurando la gravità terrestre."
                                class="cursor-help px-1 rounded">Velocità Iperbolica in Eccesso:
                        </strong>
                        {{ $sentrySummary['v_inf'] }} km/s
                    </li>
                @endauth

                <li>
                    <strong title="Magnitudine assoluta: una misura della luminosità intrinseca dell'oggetto."
                            class="cursor-help px-1 rounded">
                        Magnitudine Assoluta:
                    </strong>
                    {{ $sentrySummary['h'] }}
                </li>

            </ul>
        </div>


        <!-- Grafico Possibili Impatti Futuri -->
        @include('tools.searchSentry.components.virtualImpactorChart')



        <!-- Informazioni sul Pericolo -->
        <div class="bg-white opacity-75 px-6 py-4 rounded-2xl border-black border-2 shadow-black shadow-2xl w-96 ml-10 text-center">
            <h2 class="font-extrabold text-[18px] underline mb-2">Informazioni sul Pericolo</h2>

            <ul class="text-left">
                <li><strong>Scala Torino:</strong>
                    <span id="tsValue">{{ number_format($sentrySummary['ts_max'], 2) }}</span>
                </li>
                <li id="torinoDescription" class="mt-1 italic text-sm"></li>

                @auth()
                    <li><strong>Scala Palermo (massima):</strong> {{ number_format($sentrySummary['ps_max'],2) }}</li>
                    <li><strong>Scala Palermo (cumulativa):</strong> {{ number_format($sentrySummary['ps_cum'],2) }}
                    </li>
                @endauth

                <li class="mt-2"><strong>Numero di Possibili Impatti
                        Futuri:</strong> {{ number_format($sentrySummary['n_imp'],0) }}</li>
                <br>
                @auth()
                    <li><strong>Metodo di Analisi Usato: </strong>
                        @if($sentrySummary['method'] == "IOBS")
                            Impact pseudo-observation (IOBS)
                        @elseif($sentrySummary['method'] == "LOV")
                            Line-of-Variations (LOV)
                        @elseif($sentrySummary['method'] == "MC")
                            Monte Carlo (MC)
                        @else
                            Sconosciuto
                        @endif
                    </li>


                    <br>
                    <li>
                            <span class="relative group cursor-pointer text-blue-500 underline "> Dati usati per la computazione
                                <span class="absolute bottom-5 -left-6 hidden group-hover:block bg-gray-800 text-white text-[14px] rounded-2xl px-2 py-1 w-96 shadow-md shadow-white shadow-offset-y-[-5px] z-10">
                                    <ul class="text-left">
                                        <li><strong>Data per Calcolo Palermo Scale:</strong> {{ $sentrySummary['pdate'] ?? 'N/A' }}</li>
                                        <li><strong>Data per Calcolo di Impatto:</strong> {{ $sentrySummary['cdate'] ?? 'N/A' }}</li><br>
                                        <li><strong>Prima osservazione:</strong> {{ $sentrySummary['first_obs'] ?? 'N/A' }}</li>
                                        <li><strong>Ultima osservazione:</strong> {{ $sentrySummary['last_obs'] ?? 'N/A' }}</li><br>
                                        <li><strong>Numero di giorni di osservazioni:</strong> {{ $sentrySummary['darc'] ?? 'N/A' }}</li>
                                        <li><strong>Numero totale di osservazioni:</strong> {{ $sentrySummary['nobs'] ?? 'N/A' }}</li>
                                        <li><strong>Numero di osservazioni di ritardo radar:</strong> {{ $sentrySummary['ndel'] ?? 'N/A' }}</li>
                                        <li><strong>Numero di osservazioni radar Doppler:</strong> {{ $sentrySummary['ndop'] ?? 'N/A' }}</li>
                                        <li><strong>Numero di osservazioni satellitari ottiche:</strong> {{ $sentrySummary['nsat'] ?? 'N/A' }}</li>
                                    </ul>
                                </span>
                            </span>
                    </li>
                @endauth
                @guest()
                    <p class="font-medium italic underline"> Autenticati per visualizzare i dati Tecnici. </p>
                @endguest
            </ul>
        </div>
    </div>


</main>

</body>

{{-- @include('layouts.footer') --}}
</html>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        let tsElement = document.getElementById("tsValue");
        const descriptionElement = document.getElementById("torinoDescription");

        if (tsElement && descriptionElement) {
            const tsRaw = tsElement.textContent.trim();
            const ts = parseFloat(tsRaw);

            // Se non è un numero valido o fuori scala
            if (isNaN(ts) || ts < 0 || ts > 10) {
                descriptionElement.textContent = "⚪️ Valore non valido – la scala va da 0 a 10.";
                descriptionElement.className = "mt-1 text-sm font-medium rounded px-2 py-1 inline-block bg-gray-200 text-gray-700";
                return;
            }

            let roundedTs = Math.round(ts);
            //roundedTs = 7;
            const scaleMap = {
                0: {
                    text: "🟢 Nessun rischio – nessuna minaccia.",
                    class: "bg-green-100 text-green-800"
                },
                1: {
                    text: "🟢 Evento routinario – nessun allarme.",
                    class: "bg-green-100 text-green-800"
                },
                2: {
                    text: "🟡 Da monitorare – probabilità bassa.",
                    class: "bg-yellow-100 text-yellow-800"
                },
                3: {
                    text: "🟡 Evento interessante – ma improbabile.",
                    class: "bg-yellow-100 text-yellow-800"
                },
                4: {
                    text: "🟠 Minaccia effettiva – servono approfondimenti.",
                    class: "bg-orange-200 text-orange-900"
                },
                5: {
                    text: "🟠 Minaccia seria – potenziale distruttività.",
                    class: "bg-orange-300 text-orange-900"
                },
                6: {
                    text: "🟠 Minaccia reale – rischio significativo.",
                    class: "bg-orange-400 text-black"
                },
                7: {
                    text: "🔴 Impatto quasi certo – danni regionali gravi.",
                    class: "bg-red-400 text-black"
                },
                8: {
                    text: "🔴 Impatto certo – effetti regionali importanti.",
                    class: "bg-red-400 text-black"
                },
                9: {
                    text: "🔴 Disastro globale – impatto continentale.",
                    class: "bg-red-400 text-black"
                },
                10: {
                    text: "🔴 Estinzione – catastrofe globale certa.",
                    class: "bg-red-400 text-black"
                }
            };

            const scale = scaleMap[roundedTs];

            if (scale) {
                descriptionElement.textContent = scale.text;
                descriptionElement.className = "mt-1 text-sm font-medium rounded px-2 py-1 inline-block " + scale.class;
            } else {
                descriptionElement.textContent = "⚪️ Valore fuori scala.";
                descriptionElement.className = "mt-1 text-sm font-medium rounded px-2 py-1 inline-block bg-gray-200 text-gray-700";
            }
        }
    });
</script>

