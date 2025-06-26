{{--
    V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
    Copyright (c) 2025 Manuel Carlucci

    Licensed under CC BY-NC-SA 4.0: http://creativecommons.org/licenses/by-nc-sa/4.0/
--}}

    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('layouts.head')

<body class="antialiased min-h-screen text-white">

@include('layouts.header')

<main class="flex flex-col items-center justify-start px-4 py-4 space-y-6">

    <!-- Titolo e descrizione -->
    <section class="text-center space-y-3">
        <h2 class="text-2xl font-bold text-green-400 flex items-center">
            <span class="flex-grow h-0.5 bg-white mr-2"></span>
            &#10022; Analisi Predittiva su Impatti Futuri &#10022;
            <span class="flex-grow h-0.5 bg-white ml-2"></span>
        </h2>

        <p class="text-sm sm:text-base leading-relaxed shadow-text-white">
            Questa sezione consente di effettuare Analisi Predittiva su Impatti Futuri.
            Potrai selezionare un <strong class="underline">possibile impatto futuro</strong> fra quelli salvati fra i preferiti oppure cercandolo tramite denominazione
            e predire la sua possibile energia rilasciata confrontandolo con impatti atmosferici precedentemente avvenuti.<br>
            Ti verrà restituito un elenco di eventi storici simili  e la predizione sull’energia che potrebbe venir irradiata.
            Per raggiungere questo obiettivo verranno usate tecniche di Machine Learning.
        </p>
    </section>

    <div class="flex flex-row gap-10 items-center">
        <!-- Input di ricerca e selezione -->
        <section class="flex flex-col sm:flex-col items-center justify-center gap-4">
            <!-- Input ricerca -->
            <form id = "formConLoader" action="{{ route('predictiveAnalysis.search') }}" method="POST">
                @csrf
                <div class="flex items-center bg-white text-black rounded-full px-3 shadow-md w-72">
                    <input name="des" type="search" placeholder="Denominazione" class="ml-2 w-full bg-transparent border-0" />
                    <span> | </span>
                    <button type="submit" title ="Clicca qui per cercare" class="ml-2 material-symbols-outlined">search</button>
                </div>
            </form>


            <!-- Oppure -->
            <span class="text-sm text-white font-semibold">oppure</span>

            <!-- Dropdown preferiti -->
            @if (auth()->check() && auth()->user()->favoriteAsteroids)
                <select name="favorite_des" class="bg-white text-black px-4 py-2 rounded shadow-md w-56" onchange="submitFavoriteDes(this)">
                    <option value="">Recupera dai Preferiti</option>
                    @foreach (auth()->user()->favoriteAsteroids as $fav)
                        @if ($fav->isSentry)
                            <option value="{{ $fav->asteroid_designation }}">{{ $fav->asteroid_designation }}</option>
                        @endif
                    @endforeach
                </select>
            @endif


            <!-- Box Asteroide Selezionato -->
            <div class="bg-[#0D101F] opacity-85 border border-white rounded-xl p-4 w-80 shadow-lg mt-1">
                <h3 class="font-bold text-center underline -mt-2"> Asteroide Selezionato</h3>

                @if(isset($sentrySummary))
                    <p class = "text-center mb-2">{{ $sentrySummary['des'] }}</p>
                    <ul class="text-left text-[12px]">
                        <li>
                            <strong title="La probabilità cumulativa che l'impatto avvenga. Il calcolo è complesso e può variare anche di un ordine di grandezza."
                                    class="cursor-help px-1 rounded">
                                Probabilità di Impatto Stimata:
                            </strong>
                            <span class="underline text-yellow-500 font-bold cursor-pointer"
                                  title="{{ number_format($sentrySummary['ip'], 10) }} %">
                                  1 su {{ number_format(1 / $sentrySummary['ip'], 0, ',', '.') }}
                            </span>
                        </li>

                        <li>
                            <strong title="Velocità al momento dell'ingresso atmosferico."
                                    class="cursor-help px-1 rounded">
                                Velocità di Impatto:
                            </strong>
                            {{ number_format($sentrySummary['v_imp'], 2) }} km/s
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
                        </li>

                        <li class ="ml-1"><strong>Scala Torino:</strong>
                            <span id="tsValue">{{ number_format($sentrySummary['ts_max'], 2) }}</span>
                        </li>
                        <li id="torinoDescription" class="ml-1 italic text-sm"></li>
                    </ul>

                @elseif(isset($error))
                    <div class="text-red-400">{{ $error }}</div>
                @else
                    <p class="italic text-lg text-white text-center">Ancora Nessun Risultato</p>
                @endif
            </div>
        </section>


        <!-- Form Analisi -->
        <form id="analyzeRiskForm" action="{{ route('predictiveAnalysis.analyze') }}" method="POST">
            @csrf
            <input type="hidden" id="energy" name="energy" value="{{ $sentrySummary['energy'] ?? '' }}">
            <input type="hidden" id="velocity" name="velocity" value="{{ $sentrySummary['v_imp'] ?? '' }}">

            <!-- Bottone analisi -->
            <button id="analyzeRiskButton" type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-black font-semibold py-2 px-4 rounded-md shadow-md flex items-center gap-2 h-12 w-2/3 max-w-60">
                Analizza il Rischio Potenziale
                <span class="material-symbols-outlined">play_arrow</span>
            </button>

            <!-- Display error if any -->
            @if(session('error'))
                <div class="mt-2 text-red-500 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Show calculation result if available -->
            @if(isset($product))
                <div class="mt-3 pt-2 border-t border-gray-300">
                    <p class="font-bold">Risultato del calcolo:</p>
                    <p class="text-xl text-yellow-400">{{ number_format($product, 2) }} kT·km/s</p>
                </div>
            @endif
        </form>

        <!-- Div risultati -->
        <div class="bg-white bg-opacity-95 text-black rounded-lg p-6 h-96 max-w-screen-md overflow-auto text-center">
            @if(isset($predicted_energy))
                <div class="w-full">
                    <h3 class="text-xl font-bold mb-4">Risultati dell'Analisi Predittiva</h3>
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                        <p class="font-bold">Energia Predetta dell'Impatto:</p>
                        <p class="text-2xl">{{ number_format($predicted_energy, 2) }} kT</p>
                    </div>

                    <div class="text-left mt-4">
                        <p class="mb-2"><strong>Comparazione con eventi conosciuti:</strong></p>
                        <ul class="list-disc pl-5">
                            @if($predicted_energy < 1)
                                <li>Simile a piccole meteore che si disintegrano nell'atmosfera</li>
                            @elseif($predicted_energy < 15)
                                <li>Comparabile all'impatto di Chelyabinsk (Russia, 2013) - circa 500 kT</li>
                            @elseif($predicted_energy < 50)
                                <li>Simile all'evento di Tunguska (Russia, 1908) - circa 10-15 MT</li>
                            @else
                                <li>Potenzialmente catastrofico, simile o superiore all'impatto di Chicxulub che causò l'estinzione dei dinosauri</li>
                            @endif
                        </ul>

                        <div class="mt-4">
                            <p><strong>Possibili conseguenze:</strong></p>
                            <p class="text-sm">Questa è una previsione basata su modelli di Machine Learning e dovrebbe essere considerata solo per scopi educativi.</p>
                        </div>
                    </div>
                </div>
            @else
                <p class="italic text-lg text-black">Ancora Nessun Risultato</p>
            @endif
        </div>

    </div>

    <!--  Errori -->
    @include('components.toastNotification')

    <!-- Loader -->
    @include('components.loader')
</main>

{{-- Footer opzionale --}}
{{-- @include('layouts.footer') --}}

</body>
</html>


<script>
    /** Invio Form con favoriteAsteroids **/
    function submitFavoriteDes(selectElement) {
        let des = selectElement.value;

        if (des) {
            // Rimuove eventuali parentesi tonde
            // /[()]/ ->  Espressione regolare che cerca i caratteri ( o )
            // g -> "global", cioè sostituisce tutte le occorrenze trovate, non solo la prima.
            des = des.replace(/[()]/g, '').trim();

            const form = document.createElement('form');
            form.id = "formConLoader";
            form.method = 'POST';
            form.action = "{{ route('predictiveAnalysis.search') }}";

            const csrfToken = document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content');

            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = csrfToken;

            const desInput = document.createElement('input');
            desInput.type = 'hidden';
            desInput.name = 'des';
            desInput.value = des;

            form.appendChild(tokenInput);
            form.appendChild(desInput);

            document.body.appendChild(form);

            // Mostra il loading overlay
            document.getElementById('loadingOverlay').classList.add('flex');
            document.getElementById('loadingOverlay').classList.remove('hidden');

            form.submit();
        }
    }
</script>



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

{{--
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const analyzeRiskForm = document.getElementById('analyzeRiskForm');

        analyzeRiskForm.addEventListener('submit', function(e) {
            const energy = document.getElementById('energy').value;
            const velocity = document.getElementById('velocity').value;

            if (!energy || !velocity) {
                e.preventDefault();

                // Mostra un messaggio di errore
                alert('Seleziona prima un asteroide');
                return false;
            }

            // Mostra il loader
            const loadingOverlay = document.getElementById('loadingOverlay');
            if (loadingOverlay) {
                loadingOverlay.classList.add('flex');
                loadingOverlay.classList.remove('hidden');
            }

            return true;
        });
    });
</script>
--}}
