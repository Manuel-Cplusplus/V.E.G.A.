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



<main class="flex flex-col items-center justify-center overflow-hidden">
    <div class="flex flex-col items-center text-white px-2 py-4">
        <h2 class="text-2xl font-semibold text-center">Incontri Ravvicinati alla Terra: </h2>
        <p> {{$startDate}} <--> {{$endDate}} </p>

        <p class="mb-4 text-gray-300 mt-4">Totale Asteroidi: {{ $AsteroidCount }}</p>

        <!-- Sezione Grafico + Tabella + Grafico -->
        <div class="w-screen flex flex-col lg:flex-row justify-center gap-2">

            <!-- Grafico PHA (Sinistra) -->
            <div class=" ml-5 flex flex-col items-center bg-opacity-80 bg-white p-4 mx-2 rounded-xl border-black border-2 shadow-2xl shadow-black text-black w-full lg:w-[16%]">
                <h2 class="text-lg font-semibold mb-4 text-center">Grafico Pericolosità</h2>
                <canvas id="hazardChart" class="max-w-[160px]"></canvas>
                <div class="mt-6 text-[13px] flex flex-col gap-2 text-center">
                    <div>
                        <span class="inline-block w-4 h-4 rounded-full bg-yellow-500 mr-2"></span>
                        <span>{{ $hazardousCount }} record trovati - Classificato come pericoloso</span>
                    </div>
                    <div>
                        <span class="inline-block w-4 h-4 rounded-full bg-blue-500 mr-2"></span>
                        <span>{{ $nonHazardousCount }} record trovati - Non classificato come pericoloso</span>
                    </div>
                </div>
            </div>

            <!-- Dati invisibili per il grafico di pericolosità - per farlo vedere a LLM -->
            <div id="hazardChartData" style="display:none;">
                <pre id="hazardChartJson">
                    {!! json_encode([
                        'hazardousCount' => $hazardousCount,
                        'nonHazardousCount' => $nonHazardousCount
                    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
                </pre>
            </div>


            <!-- Input Search (Filter) -->
            <input type="search" id="AsteroidFilter" name="AsteroidFilter"
                   class=" text-black absolute top-48 right-96 transform translate-x-32 p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm w-64 placeholder:text-sky-700"
                   placeholder="Cerca Asteroide con ID/nome">


            <!-- Tabella (Centro) -->
            <div class="overflow-x-auto w-full">
                <table id="asteroid-table" class="table-auto border-collapse w-full text-center shadow-lg rounded-lg overflow-hidden">
                    <thead>
                    <tr class="bg-blue-100 text-blue-900">
                        <th class="text-[14px] px-4 py-2 whitespace-nowrap cursor-pointer" data-column="0">Asteroide ⬍</th>
                        <th class="text-[14px] px-4 py-2 whitespace-nowrap cursor-pointer" data-column="1">Diametro ⬍</th>
                        <th class="text-[14px] px-4 py-2 whitespace-nowrap cursor-pointer" data-column="2">Data di Approccio ⬍</th>
                        <th class="text-[14px] px-4 py-2 whitespace-nowrap cursor-pointer" data-column="3">Distanza (km) ⬍</th>
                        <th class="text-[14px] px-4 py-2 whitespace-nowrap cursor-pointer" data-column="4">Velocità (km/s) ⬍</th>
                        <th class="text-[14px] px-4 py-2 whitespace-nowrap cursor-pointer" data-column="5">Potenzialmente <br>Pericoloso ⬍</th>
                        <th class="text-[14px] px-4 py-2 whitespace-nowrap cursor-pointer" data-column="6">Possibile <br>Impatto Futuro ⬍</th>
                        <th class="text-[14px] px-4 py-2 whitespace-nowrap">Dettaglio</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($formattedAsteroids as $index => $asteroid)
                        <tr class="asteroid-row {{ $index % 2 == 0 ? 'bg-white text-black' : 'bg-blue-50 text-black' }}" data-index="{{ $index }}">
                            <td class="text-[12px] px-4 py-2 whitespace-nowrap">{{ $asteroid['designation'] }} <br> [ID:{{ $asteroid['id'] }}] </td>
                            <td class="text-[12px] px-4 py-2 whitespace-nowrap">{{ $asteroid['diameter'] ?? 'N/A' }} m</td>
                            <td class="text-[12px] px-4 py-2 whitespace-nowrap">{{ $asteroid['data'] ?? 'N/A' }}</td>
                            <td class="text-[12px] px-4 py-2 whitespace-nowrap">{{ $asteroid['distance'] ?? 'N/A' }}</td>
                            <td class="text-[12px] px-4 py-2 whitespace-nowrap">{{ $asteroid['velocity'] ?? 'N/A' }}</td>
                            <td class="text-[12px] px-4 py-2 whitespace-nowrap">
                                @if(isset($asteroid['hazardous']))
                                    @if($asteroid['hazardous'])
                                        <span class="text-red-600 font-bold">SI</span>
                                    @else
                                        <span class="text-green-600 font-bold">NO</span>
                                    @endif
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="text-[12px] px-4 py-2 whitespace-nowrap">
                                @if(isset($asteroid['is_sentry_object']))
                                    @if($asteroid['is_sentry_object'])
                                        <span class="text-red-600 font-bold">SI</span>
                                    @else
                                        <span class="text-green-600 font-bold">NO</span>
                                    @endif
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="text-[12px] px-4 py-2 whitespace-nowrap text-blue-600 underline">
                                <a href="{{ route('asteroid.show', ['id' => $asteroid['id']]) }}">Clicca qui</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <!-- Paginazione -->
                <div id="pagination-controls" class="flex flex-wrap gap-2 mt-6 justify-center"></div>
            </div>

            <!-- Grafico Sentry (Destra) -->
            <div class="mr-5 flex flex-col items-center bg-opacity-80 bg-white p-4 mx-2 rounded-xl border-black border-2 shadow-2xl shadow-black text-black w-full lg:w-[16%]">
                <h2 class="text-lg font-semibold mb-4 text-center">Grafico Oggetti a Rischio Impatto</h2>
                <canvas id="sentryChart" class="max-w-[160px]"></canvas>
                <div class="mt-6 text-[13px] grid grid-cols-1 gap-2 text-center">
                    <div>
                        <span class="inline-block w-4 h-4 rounded-full bg-red-500 mr-2"></span>
                        <span>{{ $sentryCount }} record trovati - Oggetti con rischio d'impatto (sentry)</span>
                    </div>
                    <div>
                        <span class="inline-block w-4 h-4 rounded-full bg-green-500 mr-2"></span>
                        <span>{{ $nonSentryCount }} record trovati - Nessun rischio d'impatto</span>
                    </div>
                </div>
            </div>

            <!-- Dati invisibili per il grafico di sentry - per LLM -->
            <div id="sentryChartData" style="display:none;">
                <pre id="sentryChartJson">
                    {!! json_encode([
                        'sentryCount' => $sentryCount,
                        'nonSentryCount' => $nonSentryCount
                    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
                </pre>
            </div>

        </div>



    <!-- Popup Modal -->
    @include('components.popUp.login-popUp')
</main>

</body>

{{-- @include('layouts.footer') --}}
</html>


<script>
    /*** Tabella **/
    document.addEventListener('DOMContentLoaded', function () {
        const table = document.getElementById('asteroid-table');
        const filterInput = document.getElementById('AsteroidFilter');
        const rows = Array.from(document.querySelectorAll('.asteroid-row'));
        const perPage = 5;
        let currentPage = 1;
        let sortDirection = {};
        let filteredRows = [...rows];

        function replaceUnavailableText(rowsSet) {
            rowsSet.forEach(row => {
                Array.from(row.children).forEach(cell => {
                    const text = cell.textContent.trim();
                    if (text === "0.00" || text === "..." || text === "0.00 m" || text === "0.00 km/s") {
                        cell.textContent = "Non Disponibile";
                    }
                });
            });
        }

        function highlightRowsByRisk(rowsSet) {
            rowsSet.forEach(row => {
                const hazardousCell = row.children[5]?.innerText.trim();
                const sentryCell = row.children[6]?.innerText.trim();

                row.classList.remove('bg-white', 'bg-blue-50', 'bg-yellow-100', 'bg-red-100', 'bg-red-200');

                if (hazardousCell === 'SI' && sentryCell === 'SI') {
                    row.classList.add('bg-red-200');
                } else if (sentryCell === 'SI') {
                    row.classList.add('bg-red-100');
                } else if (hazardousCell === 'SI') {
                    row.classList.add('bg-yellow-100');
                } else {
                    const index = parseInt(row.getAttribute('data-index'));
                    row.classList.add(index % 2 === 0 ? 'bg-white' : 'bg-blue-50');
                }
            });
        }

        function showPage(page) {
            const start = (page - 1) * perPage;
            const end = start + perPage;
            filteredRows.forEach((row, index) => {
                row.style.display = (index >= start && index < end) ? '' : 'none';
            });
            renderPaginationControls(page);
        }

        function renderPaginationControls(activePage) {
            const container = document.getElementById('pagination-controls');
            container.innerHTML = '';
            const totalPages = Math.ceil(filteredRows.length / perPage);

            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.innerText = i;
                btn.className = 'px-4 py-2 rounded-lg text-white font-semibold transition-colors duration-200 ' +
                    (i === activePage ? 'bg-blue-700 cursor-default' : 'bg-blue-500 hover:bg-blue-600');
                btn.disabled = i === activePage;
                btn.addEventListener('click', () => {
                    currentPage = i;
                    showPage(currentPage);
                });
                container.appendChild(btn);
            }
        }

        function sortTableByColumn(columnIndex) {
            const direction = sortDirection[columnIndex] === 'asc' ? 'desc' : 'asc';
            sortDirection[columnIndex] = direction;

            filteredRows.sort((a, b) => {
                const aCell = a.children[columnIndex].innerText.trim();
                const bCell = b.children[columnIndex].innerText.trim();

                if (columnIndex === 2) {
                    const aDate = new Date(aCell);
                    const bDate = new Date(bCell);
                    return direction === 'asc' ? aDate - bDate : bDate - aDate;
                }

                if ([1, 3, 4].includes(columnIndex)) {
                    const aVal = parseFloat(aCell.replace(/[^\d.-]/g, '')) || 0;
                    const bVal = parseFloat(bCell.replace(/[^\d.-]/g, '')) || 0;
                    return direction === 'asc' ? aVal - bVal : bVal - aVal;
                }

                if ([5, 6].includes(columnIndex)) {
                    const toBool = val => val === 'SI' ? 1 : 0;
                    const aVal = toBool(aCell);
                    const bVal = toBool(bCell);
                    return direction === 'asc' ? aVal - bVal : bVal - aVal;
                }

                return direction === 'asc'
                    ? aCell.localeCompare(bCell)
                    : bCell.localeCompare(aCell);
            });

            const tbody = table.querySelector('tbody');
            tbody.innerHTML = '';
            filteredRows.forEach((row, index) => {
                row.setAttribute('data-index', index);
                tbody.appendChild(row);
            });

            highlightRowsByRisk(filteredRows);
            showPage(1);
        }

        document.querySelectorAll('#asteroid-table thead th[data-column]').forEach(th => {
            th.addEventListener('click', () => {
                const columnIndex = parseInt(th.getAttribute('data-column'));
                sortTableByColumn(columnIndex);
            });
        });

        // 🔍 Filtro in tempo reale
        filterInput.addEventListener('input', function () {
            const searchTerm = this.value.trim().toLowerCase();

            filteredRows = rows.filter(row => {
                const designationCell = row.children[0]?.innerText.toLowerCase();
                return designationCell.includes(searchTerm);
            });

            const tbody = table.querySelector('tbody');
            tbody.innerHTML = '';
            filteredRows.forEach((row, index) => {
                row.setAttribute('data-index', index);
                tbody.appendChild(row);
            });

            replaceUnavailableText(filteredRows);
            highlightRowsByRisk(filteredRows);
            currentPage = 1;
            showPage(currentPage);
        });

        // Inizializzazione
        replaceUnavailableText(rows);
        highlightRowsByRisk(rows);
        showPage(currentPage);
    });
</script>


<script>
    /*** Grafico PHA **/
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('hazardChart').getContext('2d');

        const hazardous = {{ $hazardousCount }};
        const nonHazardous = {{ $nonHazardousCount }};
        const total = hazardous + nonHazardous;

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Pericoloso', 'Non Pericoloso'],
                datasets: [{
                    data: [hazardous, nonHazardous],
                    backgroundColor: ['#eab308', '#3b82f6'],
                    borderColor: ['#000', '#000'],
                    borderWidth: 1.5
                }]
            },
            options: {
                responsive: true,
                cutout: '50%',  // Percentuale di spazio vuoto al centro
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const value = context.raw;
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${context.label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>


<script>
    /*** Hazard Chart ***/
    document.addEventListener('DOMContentLoaded', function () {
        const sentryCtx = document.getElementById('sentryChart').getContext('2d');

        const sentry = {{ $sentryCount }};
        const nonSentry = {{ $nonSentryCount }};
        const totalSentry = sentry + nonSentry;

        new Chart(sentryCtx, {
            type: 'doughnut',
            data: {
                labels: ['Possibile Impatto Futuro', 'Non a Rischio'],
                datasets: [{
                    data: [sentry, nonSentry],
                    backgroundColor: ['#dc2626', '#16a34a'],
                    borderColor: ['#000', '#000'],
                    borderWidth: 1.5
                }]
            },
            options: {
                responsive: true,
                cutout: '50%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const value = context.raw;
                                const percentage = ((value / totalSentry) * 100).toFixed(1);
                                return `${context.label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>

