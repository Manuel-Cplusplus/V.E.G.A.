{{--
    V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
    Copyright (c) 2025 Manuel Carlucci

    Licensed under CC BY-NC-SA 4.0: http://creativecommons.org/licenses/by-nc-sa/4.0/
--}}

<div class="flex flex-col items-center text-white px-4 py-4">
    <h2 class="text-2xl font-semibold">Asteroidi Filtrati</h2>

    <!-- Icona per visualizzare i filtri -->
    <button id="filter-icon" class="text-white hover:text-blue-600 ml-4 text-3xl" title="Clicca qui per Visualizzare i Filtri Applicati">
        <i class="fas fa-filter"></i>
    </button>

    <p class="text-gray-300"> Alcuni risultati potrebbero risultare "Non Disponibili" tramite una prima Analisi. <br>
        Si consiglia di vederne i dettagli così da poter accedere anche a tali dati.
    </p>

    <p class="mb-4 text-gray-300 mt-4">Totale Asteroidi: {{ $AsteroidCount }}</p>

    @include('components.popUp.filters-popUp', ['filters' => $filters])


    <!-- Input Search (Filter) -->
    <input type="search" id="AsteroidFilter" name="AsteroidFilter"
           class=" text-black absolute top-64 right-96 transform translate-x-32 p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm w-64 placeholder:text-sky-700"
           placeholder="Cerca Asteroide con ID/nome">


    <div class="overflow-x-auto">
        <table id="asteroid-table" class="table-auto border-collapse w-full max-w-6xl text-center shadow-lg rounded-lg overflow-hidden">
            <thead>
            <tr class="bg-blue-100 text-blue-900">
                <th class="text-[18px] px-4 py-2 whitespace-nowrap cursor-pointer" data-column="0">Asteroide ⬍</th>
                <th class="text-[18px] px-4 py-2 whitespace-nowrap cursor-pointer" data-column="1">Diametro ⬍</th>
                <th class="text-[18px] px-4 py-2 whitespace-nowrap cursor-pointer" data-column="2">Data di Approccio ⬍</th>
                <th class="text-[18px] px-4 py-2 whitespace-nowrap cursor-pointer" data-column="3">Distanza ({{$formattedAsteroids[0]['unit']}}) ⬍</th>
                <th class="text-[18px] px-4 py-2 whitespace-nowrap cursor-pointer" data-column="4">Velocità (km/s) ⬍</th>
                <th class="text-[18px] px-4 py-2 whitespace-nowrap cursor-pointer" data-column="5">Magnitudine ⬍</th>
                <th class="text-[18px] px-4 py-2 whitespace-nowrap">Dettaglio</th>
            </tr>
            </thead>
            <tbody>
            @foreach($formattedAsteroids as $index => $asteroid)
                <tr class="asteroid-row {{ $index % 2 == 0 ? 'bg-white text-black' : 'bg-blue-50 text-black' }}" data-index="{{ $index }}">
                    <td class="text-[14px] px-4 py-2 whitespace-nowrap">{{ $asteroid['fullname'] }}</td>
                    <td class="text-[14px] px-4 py-2 whitespace-nowrap">{{ $asteroid['diameter'] ?? '...' }} m</td>
                    <td class="text-[14px] px-4 py-2 whitespace-nowrap">{{ $asteroid['date'] ?? '...' }}</td>
                    <td class="text-[14px] px-4 py-2 whitespace-nowrap">
                        <span class="text-blue-700 underline cursor-pointer relative group"
                              onclick="showDistancePopup({{ $asteroid['miss_distance_lunar'] ?? 'null' }})">
                              {{ $asteroid['distance'] ?? '...' }} km
                        <span class="absolute left-1/2 transform -translate-x-1/2 hidden group-hover:block bg-gray-800 text-white text-[14px] rounded-2xl px-2 py-1 w-auto shadow-white shadow-offset-y-[-5px] shadow-2xl z-10 mt-1">
                            Clicca qui per vedere una rappresentazione realistica della distanza
                        </span>
                        </span>
                    </td>
                    <td class="text-[14px] px-4 py-2 whitespace-nowrap">{{ $asteroid['velocity_abs_km_s'] ?? '...' }}</td>
                    <td class="text-[14px] px-4 py-2 whitespace-nowrap">{{ $asteroid['magnitude'] ?? '...' }}</td>
                    <td class="text-[14px] px-4 py-2 whitespace-nowrap text-blue-600 underline">
                        @if(!empty($asteroid['id']))
                            <a href="{{ route('asteroid.show', ['id' => $asteroid['fullname']]) }}">Clicca qui</a>
                            {{--<span class="text-gray-400">N/A</span>--}}
                        @elseif(!empty($asteroid['designation']))
                            <a href="{{ route('asteroid.show', ['id' => $asteroid['designation']]) }}">Clicca qui</a>
                        @else
                            <span class="text-gray-400">N/A</span>
                        @endif

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div id="pagination-controls" class="flex flex-wrap gap-2 mt-6"></div>


    <!-- Visualizzazione 3D -->
    @include('tools.generalTools.distanceVisualization')
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const table = document.getElementById('asteroid-table');
        const rows = Array.from(document.querySelectorAll('.asteroid-row'));
        const perPage = 5;
        let currentPage = 1;
        let sortDirection = {};
        let filteredRows = [...rows]; // Righe filtrate

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

                if ([1, 3, 4, 5].includes(columnIndex)) {
                    const aVal = parseFloat(aCell.replace(/[^\d.-]/g, '')) || 0;
                    const bVal = parseFloat(bCell.replace(/[^\d.-]/g, '')) || 0;
                    return direction === 'asc' ? aVal - bVal : bVal - aVal;
                }

                return direction === 'asc'
                    ? aCell.localeCompare(bCell)
                    : bCell.localeCompare(aCell);
            });

            const tbody = table.querySelector('tbody');
            tbody.innerHTML = '';

            filteredRows.forEach((row, index) => {
                row.classList.remove('bg-white', 'bg-blue-50');
                row.classList.add(index % 2 === 0 ? 'bg-white' : 'bg-blue-50');
                tbody.appendChild(row);
            });

            showPage(1);
        }

        document.querySelectorAll('#asteroid-table thead th[data-column]').forEach(th => {
            th.addEventListener('click', () => {
                const columnIndex = parseInt(th.getAttribute('data-column'));
                sortTableByColumn(columnIndex);
            });
        });

        // Filtro per nome o ID
        document.getElementById('AsteroidFilter').addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase();
            filteredRows = rows.filter(row => {
                const id = row.children[0]?.innerText.toLowerCase();
                const name = row.children[1]?.innerText.toLowerCase();
                return id.includes(searchTerm) || name.includes(searchTerm);
            });

            const tbody = table.querySelector('tbody');
            tbody.innerHTML = '';

            filteredRows.forEach((row, index) => {
                row.classList.remove('bg-white', 'bg-blue-50');
                row.classList.add(index % 2 === 0 ? 'bg-white' : 'bg-blue-50');
                tbody.appendChild(row);
            });

            showPage(1);
        });

        replaceUnavailableText();
        showPage(currentPage);
    });
</script>


