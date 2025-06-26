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

    <!-- Descrizione -->
    <section class="text-center mb-4 mt-4">
        <h2 class="text-2xl font-bold text-green-400 flex items-center">
            <span class="flex-grow h-0.5 bg-white mr-2"></span>
            &#10022; Impatti Atmosferici &#10022;
            <span class="flex-grow h-0.5 bg-white ml-2"></span>
        </h2>
        <p class="text-[14px] text-white">
            Questa sezione consente di ricercare informazioni sugli eventi Fireball, ovvero sulle meteore
            eccezionalmente luminose.
            Per maggiori dettagli visitare: <a href="https://cneos.jpl.nasa.gov/fireballs/"
                                               class="underline text-yellow-500 hover:text-yellow-600">NASA Fireball</a>
            <br>
            Posiziona il puntatore sulla velocità per ottenere la velocità delle sue componenti secondo li assi x,y,z.
            Si noti che alcuni campi possono essere indefiniti, in quanto tale dato potrebbe non essere stato registrato
            nel database della NASA.
        </p>
    </section>
    <p class="text-gray-300 mr-60">Totale Risultati: {{ $fireballCount }}</p>

    <div class="flex col-span-2 gap-5">
        <!-- Tabella -->
        <div class="w-full">
            <table id="asteroid-table"
                   class="table-auto border-collapse w-full text-center shadow-lg rounded-lg overflow-hidden">
                <thead>
                <tr class="bg-blue-100 text-blue-900">
                    <th class="text-[14px] px-4 py-2 whitespace-nowrap cursor-pointer" data-column="0">Data di <br>
                        Massima Luminosità ⬍
                    </th>
                    <th class="text-[14x] px-4 py-2 whitespace-nowrap cursor-pointer" data-column="1">Coordinate
                        (gradi) ⬍
                    </th>
                    <th class="text-[14x] px-4 py-2 whitespace-nowrap cursor-pointer" data-column="2">Altitudine (km)
                        ⬍
                    </th>
                    <th class="text-[14x] px-4 py-2 whitespace-nowrap cursor-pointer" data-column="3">Velocità (km/s)
                        ⬍
                    </th>
                    <th class="text-[14x] px-4 py-2 whitespace-nowrap cursor-pointer" data-column="4">Energia Irradiata
                        (J) ⬍
                    </th>
                    <th class="text-[14px] px-4 py-2 whitespace-nowrap cursor-pointer" data-column="5">Energia i Impatto
                        (kT) ⬍
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($fireballs as $index => $fireball)
                    <tr class="asteroid-row {{ $index % 2 == 0 ? 'bg-white text-black' : 'bg-blue-50 text-black' }}"
                        data-index="{{ $index }}">
                        <td class="text-[12px] px-4 py-2 whitespace-nowrap">{{ $fireball['date'] ?? 'N/A' }}</td>
                        <td class="text-[12px] px-4 py-2 whitespace-nowrap cursor-default">{{ $fireball['coordinate'] }}</td>
                        <td class="text-[12px] px-4 py-2 whitespace-nowrap">{{ $fireball['altitude'] }}</td>
                        <td class="text-[12px] px-4 py-2 whitespace-nowrap">
                            @if($fireball['speed'] !== 'N/A')
                                <span class="text-blue-600 underline cursor-pointer" title="{{ $fireball['speed_components'] }}">
                                    {{ $fireball['speed'] }}
                                </span>
                            @else
                                {{ $fireball['speed'] }}
                            @endif
                        </td>

                        <td class="text-[12px] px-4 py-2 whitespace-nowrap">{{ $fireball['energy'] }}</td>
                        <td class="text-[12px] px-4 py-2 whitespace-nowrap">{{ $fireball['impact'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>


            <!-- Paginazione -->
            <div class=" mb-4 mt-4 flex flex-row items-center justify-between">
                <p class="text-sm text-white ml-2">Vai alla pagina:</p>
                <input type="number" id="page-input" min="0"
                       class="px-1 py-2 rounded-lg text-black font-semibold ml-4 mr-4 text-center w-20"
                       placeholder="1, 2, ..."/>
                <div class="mr-48 flex-1 flex justify-center">
                    <div id="pagination-controls" class="flex flex-wrap gap-2 justify-center"></div>
                </div>
            </div>


            <!-- Bottoni Grafici + Filtri Applicati -->
            <div class="flex flex-row gap-16">
                <!-- Pulsante Grafico 1 -->
                <div class="flex items-center justify-items-center">
                    <button onclick="openEnergyChart()"
                            class="opacity-90 bg-orange-600 hover:bg-orange-400 text-black px-10 py-2 rounded-2xl flex flex-col items-center gap-2 border-black border-2 relative">
                        <span> Visualizza Grafico <br> relativo all'Energia di Impatto </span>

                        <!-- Contenitore icone -->
                        <span class="relative w-10 h-10">
                    <!-- Icona del grafico (sotto) -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-black opacity-80" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M3 3v18h18"/>
                        <path d="M18 17V9"/>
                        <path d="M13 17V5"/>
                        <path d="M8 17v-3"/>
                    </svg>

                            <!-- Icona del fulmine (sopra) -->
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="absolute bottom-5 left-7 w-6 h-6 text-yellow-400 opacity-90 z-10 fill-yellow-400 stroke-yellow-400"
                         viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1.5">
                        <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                    </svg>
                </span>
                    </button>
                </div>


                <!-- Filtri Applicati -->
                <div
                    class="bg-white opacity-75 px-6 pt-2 pb-1 rounded-2xl border-black border-2 shadow-black shadow-2xl w-1/3">
                    <div class="flex flex-col items-center">
                        <h3 class="font-extrabold mb-2 text-[16px] underline">Filtri Applicati</h3>
                        <ul class="text-[13px]">
                            <li><strong>Data:</strong>
                                {{ isset($filters['date-min']) ? $filters['date-min'] : 'N/A' }} -
                                {{ isset($filters['date-max']) ? $filters['date-max'] : 'N/A' }}
                            </li>

                            <li><strong>Altitudine:</strong>
                                {{ isset($filters['alt-min']) ? $filters['alt-min'] : 'N/A' }} -
                                {{ isset($filters['alt-max']) ? $filters['alt-max'] : 'N/A' }}
                            </li>

                            <li><strong>Energia Irradiata (J):</strong>
                                {{ isset($filters['energy-min']) ? $filters['energy-min'] : 'N/A' }} -
                                {{ isset($filters['energy-max']) ? $filters['energy-max'] : 'N/A' }}
                            </li>

                            <li><strong>Energia di Impatto (kT):</strong>
                                {{ isset($filters['impact-e-min']) ? $filters['impact-e-min'] : 'N/A' }} -
                                {{ isset($filters['impact-e-max']) ? $filters['impact-e-max'] : 'N/A' }}
                            </li>
                        </ul>
                    </div>
                </div>


                <!-- Pulsante Grafico 2 -->
                <div class="flex items-center justify-items-center">
                    <button onclick="openImpactChart()"
                            class="opacity-90 bg-orange-600 hover:bg-orange-400 text-black px-10 py-2 rounded-2xl flex flex-col items-center gap-2 border-black border-2 relative">
                        <span> Visualizza Grafico <br> relativo al Numero di Impatti </span>

                        <!-- Contenitore icone -->
                        <span class="relative w-10 h-10">
                    <!-- Icona del grafico (sotto) -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-black opacity-80" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M3 3v18h18"/>
                        <path d="M18 17V9"/>
                        <path d="M13 17V5"/>
                        <path d="M8 17v-3"/>
                    </svg>

                            <!-- Icona del fulmine (sopra) -->
                    <i class="fas fa-meteor text-yellow-400 absolute bottom-6 left-6" style="font-size: 24px;"></i>
                </span>
                    </button>
                </div>
            </div>


        </div>


        <!-- Filtri -->
        <div
            class="bg-white opacity-85 px-6 py-4 rounded-2xl border-black border-2 shadow-black shadow-2xl max-w-lg mx-auto">
            <div class="flex flex-col items-center">
                <h3 class="font-extrabold mb-2 text-[18px] underline"> Filtri </h3>
            </div>
            {{-- Gestione Errore
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Errore:</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
            --}}

            <form id="formConLoader" action="{{route('fireball.search')}}" method="POST"
                  class="w-full max-w-7xl rounded-lg text-black items-center">
                @csrf  {{-- Token CSRF per sicurezza --}}

                <div class="mt-4 flex col-span-2 gap-4">
                    <!-- Data Minima -->
                    <div class="mb-2">
                        <label for="date-min" class="block text-sm font-medium text-gray-700">Data Minima</label>
                        <input type="date" id="date-min" name="date-min"
                               class="mt-1 block px-4 py-2 text-sm border-gray-300 rounded-md shadow-sm"/>
                    </div>

                    <!-- Data Massima -->
                    <div class="mb-2">
                        <label for="date-max" class="block text-sm font-medium text-gray-700">Data Massima</label>
                        <input type="date" id="date-max" name="date-max"
                               class="mt-1 block px-4 py-2 text-sm border-gray-300 rounded-md shadow-sm"/>
                    </div>
                </div>

                <p class="mt-4 mb-2 text-sm font-medium text-gray-700 text-center">Altitudine (km)</p>
                <div class="flex col-span-2 gap-12">
                    <!-- Altitudine Minima -->
                    <div class="mb-2 flex col-span-2 gap-2">
                        <label for="alt-min"
                               class="block text-sm font-medium text-gray-700 text-center items-center flex">Minima</label>
                        <input type="number" id="alt-min" name="alt-min" step="0.01" min="0"
                               class="mt-1 block w-20 px-4 py-2 text-sm border-gray-300 rounded-md shadow-sm"/>
                    </div>

                    <!-- Altitudine Massima -->
                    <div class="mb-2 flex col-span-2 gap-2">
                        <label for="alt-max"
                               class="block text-sm font-medium text-gray-700 text-center items-center flex">Massima</label>
                        <input type="number" id="alt-max" name="alt-max" step="0.01" min="0"
                               class="mt-1 block w-20 px-4 py-2 text-sm border-gray-300 rounded-md shadow-sm"/>
                    </div>
                </div>


                <div class="flex col-span-2 gap-6">
                    <div>
                        <p class="mt-4 mb-2 text-sm font-medium text-gray-700 text-center">Energia Irradiata (× 10<sup>10</sup> J)</p>
                        <!-- Energia Irradiata Minima -->
                        <div class="mb-2 flex col-span-2 gap-2">
                            <label for="energy-min"
                                   class="block text-sm font-medium text-gray-700 text-center items-center flex">Minima</label>
                            <input type="number" id="energy-min" name="energy-min" step="0.01" min="0"
                                   class="mt-1 ml-2 block w-20 px-4 py-2 text-sm border-gray-300 rounded-md shadow-sm"/>
                        </div>

                        <!-- Energia Irradiata Massima -->
                        <div class="mb-2 flex col-span-2 gap-2">
                            <label for="energy-max"
                                   class="block text-sm font-medium text-gray-700 text-center items-center flex">Massima</label>
                            <input type="number" id="energy-max" name="energy-max" step="0.01" min="0"
                                   class="mt-1 block w-20 px-4 py-2 text-sm border-gray-300 rounded-md shadow-sm"/>
                        </div>
                    </div>

                    <div>
                        <p class="mt-4 mb-2 text-sm font-medium text-gray-700 text-center">Energia di Impatto (kT)</p>
                        <!-- Energia di Impatto Minima -->
                        <div class="mb-2 flex col-span-2 gap-2">
                            <label for="impact-e-min"
                                   class="block text-sm font-medium text-gray-700 text-center items-center flex">Minima</label>
                            <input type="number" id="impact-e-min" name="impact-e-min" step="0.01" min="0"
                                   class="mt-1 ml-2 block w-20 px-4 py-2 text-sm border-gray-300 rounded-md shadow-sm"/>
                        </div>

                        <!-- Energia di Impatto Massima -->
                        <div class="mb-2 flex col-span-2 gap-2">
                            <label for="impact-e-max"
                                   class="block text-sm font-medium text-gray-700 text-center items-center flex">Massima</label>
                            <input type="number" id="impact-e-max" name="impact-e-max" step="0.01" min="0"
                                   class="mt-1 block w-20 px-4 py-2 text-sm border-gray-300 rounded-md shadow-sm"/>
                        </div>
                    </div>

                </div>
                <!-- Pulsante Cerca -->
                <div class="mt-2 flex justify-center relative">
                    <button type="submit"
                            class="bg-yellow-500 hover:bg-yellow-600 text-black px-6 py-2 rounded-2xl flex items-center border-black border-2">
                        Cerca
                        <img src="media/icons/ricerca_nero.png" alt="Cerca Asteroide" class="w-6 h-6 ml-4">
                    </button>
                </div>
            </form>

        </div>


    </div>


    <!-- Toast -->
    @include('components.toastNotification')

    <!-- Loader -->
    @include('components.loader')

    <!-- Grafici -->
    @include('tools.searchFireball.energyChart')
    <!-- Grafici -->
    @include('tools.searchFireball.impactChart')
</main>

</body>

{{-- @include('layouts.footer') --}}
</html>


<script>
    /*** Tabella **/
    document.addEventListener('DOMContentLoaded', function () {
        const table = document.getElementById('asteroid-table');
        const rows = Array.from(document.querySelectorAll('.asteroid-row'));
        const perPage = 5;
        const totalPages = Math.ceil(rows.length / perPage);
        let currentPage = 1;
        let sortDirection = {};

        const pageInput = document.getElementById('page-input');
        pageInput.addEventListener('input', function () {
            let pageNumber = parseInt(pageInput.value);
            if (pageNumber >= 1 && pageNumber <= totalPages) {
                currentPage = pageNumber;
                showPage(currentPage);
            }
        });

        function updateRowColors() {
            let visibleRows = rows.filter(row => row.style.display !== 'none');
            visibleRows.forEach((row, i) => {
                row.classList.remove('bg-white', 'bg-blue-50');
                row.classList.add(i % 2 === 0 ? 'bg-white' : 'bg-blue-50');
            });
        }

        function replaceUnavailableText() {
            rows.forEach(row => {
                Array.from(row.children).forEach(cell => {
                    const text = cell.textContent.trim();
                    if (text === "0.00" || text === "..." || text === "0.00 m" || text === "0.00 km/s") {
                        cell.textContent = "Non Disponibile";
                    }
                });
            });
        }


        function showPage(page) {
            const start = (page - 1) * perPage;
            const end = start + perPage;
            rows.forEach((row, index) => {
                row.style.display = (index >= start && index < end) ? '' : 'none';
            });
            updateRowColors();
            renderPaginationControls(page);
        }

        function renderPaginationControls(activePage) {
            const container = document.getElementById('pagination-controls');
            container.innerHTML = '';

            const windowSize = 6;
            const windowStart = Math.max(2, activePage - Math.floor(windowSize / 2));
            const windowEnd = Math.min(totalPages - 1, windowStart + windowSize - 1);

            // Primo elemento sempre visibile
            const firstButton = createPageButton(1, activePage);
            container.appendChild(firstButton);

            // Ellissi se necessario
            if (windowStart > 2) {
                const ellipsis = document.createElement('span');
                ellipsis.className = 'text-white px-2 py-2 text-[16px] font-bold';
                ellipsis.innerText = '. . .';
                container.appendChild(ellipsis);
            }

            // Aggiungi le pagine nella finestra
            for (let i = windowStart; i <= windowEnd; i++) {
                const pageButton = createPageButton(i, activePage);
                container.appendChild(pageButton);
            }

            // Ellissi se necessario
            if (windowEnd < totalPages - 1) {
                const ellipsis = document.createElement('span');
                ellipsis.className = 'text-white px-2 py-2 text-[16px] font-bold';
                ellipsis.innerText = '...';
                container.appendChild(ellipsis);
            }

            // Ultimo elemento sempre visibile
            const lastButton = createPageButton(totalPages, activePage);
            container.appendChild(lastButton);
        }

        function createPageButton(pageNumber, activePage) {
            const btn = document.createElement('button');
            btn.innerText = pageNumber;
            btn.className = 'px-4 py-2 rounded-lg text-white font-semibold transition-colors duration-200 ' +
                (pageNumber === activePage ? 'bg-blue-700 cursor-default' : 'bg-blue-500 hover:bg-blue-600');
            btn.disabled = pageNumber === activePage;
            btn.addEventListener('click', () => {
                currentPage = pageNumber;
                showPage(currentPage);
            });
            return btn;
        }


        function sortTableByColumn(columnIndex) {
            const direction = sortDirection[columnIndex] === 'asc' ? 'desc' : 'asc';
            sortDirection[columnIndex] = direction;

            rows.sort((a, b) => {
                const aCell = a.children[columnIndex].innerText.trim();
                const bCell = b.children[columnIndex].innerText.trim();

                if (columnIndex === 0) { // Data
                    const aDate = new Date(aCell);
                    const bDate = new Date(bCell);
                    return direction === 'asc' ? aDate - bDate : bDate - aDate;
                }

                if ([1, 2, 3, 4, 5].includes(columnIndex)) { // Numeri come altitudine, velocità, energia, etc.
                    const aVal = parseFloat(aCell.replace(/[^\d.-]/g, '')) || 0;
                    const bVal = parseFloat(bCell.replace(/[^\d.-]/g, '')) || 0;
                    return direction === 'asc' ? aVal - bVal : bVal - aVal;
                }

                if (columnIndex === 6) { // Dettagli
                    return direction === 'asc'
                        ? aCell.localeCompare(bCell)
                        : bCell.localeCompare(aCell);
                }

                return direction === 'asc'
                    ? aCell.localeCompare(bCell)
                    : bCell.localeCompare(aCell);
            });

            const tbody = table.querySelector('tbody');
            rows.forEach((row, index) => {
                tbody.appendChild(row);  // Aggiungi la riga ordinata al corpo della tabella senza alterare le classi CSS.
            });

            showPage(1);
            updateRowColors();
        }

        document.querySelectorAll('#asteroid-table thead th[data-column]').forEach(th => {
            th.addEventListener('click', () => {
                const columnIndex = parseInt(th.getAttribute('data-column'));
                sortTableByColumn(columnIndex);
            });
        });

        replaceUnavailableText();
        showPage(currentPage);
    });
</script>

