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

    <div class = "flex items-center justify-items-center">
        <img src="{{ asset('media/icons/cuore_contorno_bianco.png') }}" alt="Preferiti" class="w-10 h-10 mt-6" title="Preferiti">
        <h2 class="text-3xl font-bold  mt-6 mr-4 ml-4 text-white">I tuoi asteroidi preferiti</h2>
        <img src="{{ asset('media/icons/cuore_contorno_bianco.png') }}" alt="Preferiti" class="w-10 h-10 mt-6" title="Preferiti">
    </div>
    <p class="text-white mt-2 mb-6" style="text-shadow: 2px 2px 6px rgba(0,0,0,0.8), -2px -2px 6px rgba(0,0,0,0.6);">
        Riceverai notifiche sul cambio data di approccio con la Terra, cambio di stato (da impattante sulla Terra a innocuo e viceversa), cambio di probabilità, data e rischio di impatto.
    </p>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($asteroids as $asteroid)
            <div class="bg-white bg-opacity-85 px-4 py-4 rounded-lg text-black w-full flex-shrink-0 border-black border-2 relative shadow-[6px_0_6px_rgba(0,0,0,0.6),-6px_0_6px_rgba(0,0,0,0.6)]">

                {{-- Rimozione dai preferiti --}}
                <form action="{{ route('favorites.remove') }}" method="POST">
                    @csrf
                    <input type="hidden" name="asteroid_id" value="{{ $asteroid->asteroid_id }}">
                    <input type="hidden" name="asteroid_designation" value="{{ $asteroid->asteroid_designation }}">
                    <button class="preferitiBtn absolute top-2 right-2" type="submit" data-favorite="true">
                        <img src="{{ asset('media/icons/cuore_pieno.png') }}" alt="Preferiti" class="w-8 h-8"
                             title="Rimuovi dai Preferiti">
                    </button>
                </form>

                {{-- Icona info: rimanda alla pagina di dettaglio --}}
                <a href="{{ route('asteroid.show', ['id' => $asteroid->asteroid_id]) }}">
                    <img src="{{ asset('media/icons/informazioni.png') }}" alt="Informazioni"
                         class="w-8 h-8 absolute top-11 right-2 hover:scale-110 transition-transform duration-200">
                </a>

                {{-- Icona asteroide --}}
                <img src="{{ asset('media/icons/asteroide.png') }}" alt="Asteroide"
                     class="w-12 h-12 absolute top-4 left-3">

                <h3 class="text-lg font-bold text-center">{{ $asteroid->asteroid_designation }}</h3>
                <p class="text-center text-[16px] mb-6">[ID: {{ $asteroid->asteroid_id }}]</p>

                <p class="text-[14px]">Prossimo passaggio ravvicinato: {{ $asteroid->cad }}</p>

                <p class="text-[14px]">Possibile impatto futuro:
                    <span class="text-[16px] font-extrabold {{ $asteroid->isSentry ? 'text-red-500' : 'text-green-500' }}">
                        {{ $asteroid->isSentry ? 'Sì' : 'No' }}
                    </span>
                </p>

                @if ($asteroid->isSentry)

                    <p class="text-[14px] cursor-help rounded" title="La probabilità cumulativa che l'impatto avvenga. Il calcolo è complesso e può variare anche di un ordine di grandezza.">
                        Probabilità di impatto:
                        <span class="underline text-blue-600 cursor-pointer"
                              title="{{ number_format($asteroid->impact_probability, 10) }} %">
                              1 su {{ number_format(1 / $asteroid->impact_probability, 0, ',', '.') }}
                        </span>
                    </p>
                    <p class="text-[14px]">Data impatto stimata: {{ $asteroid->impact_date }}</p>
                    <p class="tsValue text-[14px]">Scala Torino: {{ $asteroid->torino_scale }}</p>
                    <p class="torinoDescription mt-1 italic text-sm"></p>

                    <a href="{{ route('sentry.show', ['des' => $asteroid->asteroid_id]) }}"
                       class="text-[14px] text-blue-700 underline">
                        Vedi Dettagli Possibile Impatto Futuro
                    </a>
                @endif

            </div>
        @empty
            <p class="text-gray-700 col-span-4 text-center">Non hai ancora asteroidi tra i preferiti.</p>
        @endforelse
    </div>


    <!-- Popup Modal -->
    @include('components.popUp.login-popUp')
</main>

</body>

{{-- @include('layouts.footer') --}}
</html>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        const tsElements = document.querySelectorAll(".tsValue");

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

        tsElements.forEach(function (tsElement) {
            const descriptionElement = tsElement.nextElementSibling;
            const tsRaw = tsElement.textContent.replace("Scala Torino:", "").trim();
            const ts = parseFloat(tsRaw);

            if (isNaN(ts) || ts < 0 || ts > 10) {
                descriptionElement.textContent = "⚪️ Valore non valido – la scala va da 0 a 10.";
                descriptionElement.className = "mt-1 text-sm font-medium rounded px-2 py-1 bg-gray-200 text-gray-700";
                return;
            }

            const roundedTs = Math.round(ts);
            const scale = scaleMap[roundedTs];

            if (scale) {
                descriptionElement.textContent = scale.text;
                descriptionElement.className = "mt-1 text-sm font-medium rounded px-2 py-1 " + scale.class;
            } else {
                descriptionElement.textContent = "⚪️ Valore fuori scala.";
                descriptionElement.className = "mt-1 text-sm font-medium rounded px-2 py-1 bg-gray-200 text-gray-700";
            }
        });
    });
</script>
