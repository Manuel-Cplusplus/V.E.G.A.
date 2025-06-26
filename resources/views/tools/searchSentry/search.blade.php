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
            &#10022; Possibili Impatti Futuri &#10022;
            <span class="flex-grow h-0.5 bg-white ml-2"></span>
        </h2>
        <p class="text-[16px] text-white">
            Questa sezione consente di ricercare informazioni sui probabili impatti futuri sulla Terra. <br>
            Clicca sul nome dell’asteroide per vedere dettagli aggiuntivi e posiziona il cursore sui campi per vederne le descrizioni. <br>
            Per maggiori dettagli visitare: <a href="https://cneos.jpl.nasa.gov/sentry/"
                                               class="underline text-yellow-500 hover:text-yellow-600">NASA Sentry</a>
        </p>
    </section>

    <p class="text-gray-300">
        Totale Risultati: {{ $SentryCount }}
        <span id="show-filters" class="cursor-pointer underline">(Clicca qui per vedere i filtri applicati)</span>
    </p>

    <div class="flex col-span-3 gap-5">

        <!-- Info Aggiuntive -->
        <div id="asteroid-info" class=" ml-5 flex flex-col items-center bg-opacity-80 bg-white p-4 mx-2 rounded-xl border-black border-2 shadow-2xl shadow-black text-black w-1/4">
            <h2 class="text-lg font-semibold mb-4 text-center underline">Asteroide Selezionato</h2>
            <div class="flex flex-row items-center text-center gap-6">
                <img src="media/icons/asteroide.png" alt="Asteroide">
                <p id="asteroid-name" class="text-[14px]">Seleziona un asteroide dalla tabella</p>
            </div>
            <br>
            <a id="link-stats" href="#" class="text-blue-500 hover:underline"></a><br>
            <a id="link-impact" href="#" class="text-blue-500 hover:underline"></a><br>
            <a id="link-nasa" href="#" class="text-blue-500 hover:underline"></a>
            {{-- Gestione Errore
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Errore:</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
            --}}
        </div>


        <!-- Tabella -->
        <div class="w-full">
            <table id="asteroid-table"
                   class="table-auto border-collapse w-full text-center shadow-lg rounded-lg overflow-hidden">
                <thead>
                <tr class="bg-blue-100 text-blue-900">
                    <th class="text-[14px] px-4 py-2 whitespace-nowrap cursor-pointer" data-column="0">Asteroide ⬍ </th>
                    <th class="text-[14px] px-4 py-2 whitespace-nowrap cursor-pointer" data-column="1">Range di Anni di <br> Probabile Impatto ⬍ </th>
                    <th class="text-[14px] px-4 py-2 whitespace-nowrap cursor-pointer" data-column="2">Diametro (m) ⬍</th>
                    <th class="text-[14px] px-4 py-2 whitespace-nowrap cursor-pointer" data-column="3">Velocità (km/s) ⬍ </th>
                    <th class="text-[14px] px-4 py-2 whitespace-nowrap cursor-pointer" data-column="4">Probabilità di Impatto ⬍ </th>
                    {{--
                    <th class="text-[16px] px-4 py-2 whitespace-nowrap cursor-pointer" data-column="5">Scala Torino ⬍ </th>
                    <th class="text-[16px] px-4 py-2 whitespace-nowrap cursor-pointer" data-column="6">Scala Palermo <br> (massimo)  ⬍ </th>
                    <th class="text-[16px] px-4 py-2 whitespace-nowrap cursor-pointer" data-column="7">Scala Palermo <br> (cumulativo) ⬍ </th>
                    --}}
                </tr>
                </thead>
                <tbody>
                @foreach($SentryData as $index => $Sentry)
                    <tr class="asteroid-row {{ $index % 2 == 0 ? 'bg-white text-black' : 'bg-blue-50 text-black' }}">
                        <td class="text-[13px] px-4 py-2 whitespace-nowrap">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="selected_asteroid" class="asteroid-radio"
                                       data-des="{{ $Sentry['des'] }}"
                                       data-id="{{ $Sentry['id'] }}"
                                       data-link1="{{ route('asteroid.show', ['id' => $Sentry['des']]) }}"
                                       data-link2="{{ route('sentry.show', ['des' => $Sentry['des']]) }}"
                                       data-link3="https://cneos.jpl.nasa.gov/sentry/details.html#?des={{ $Sentry['des'] }}">
                                <span>{{ $Sentry['des'] }}</span>
                            </label>
                        </td>
                        <td class="text-[13px] px-4 py-2 whitespace-nowrap">{{ $Sentry['date']}}</td>
                        <td class="text-[13px] px-4 py-2 whitespace-nowrap">{{number_format($Sentry['diameter'])}} </td>
                        <td class="text-[13px] px-4 py-2 whitespace-nowrap">{{$Sentry['velocity']}}</td>
                        <td class="text-[13px] px-4 py-2 whitespace-nowrap">{{($Sentry['probability'])}}</td>
                        {{--
                        <td class="text-[14px] px-4 py-2 whitespace-nowrap">{{$Sentry['TS'] }}</td>
                        <td class="text-[14px] px-4 py-2 whitespace-nowrap">{{$Sentry['PS-max']}}</td>
                        <td class="text-[14px] px-4 py-2 whitespace-nowrap">{{$Sentry['PS-cum']}}</td>
                        --}}
                    </tr>
                @endforeach
                </tbody>
            </table>


            <!-- Paginazione -->
            <div class="mb-8 mt-4 flex flex-row items-center justify-between w-full">
                <p class="text-sm text-white mr-2">Vai alla pagina:</p>
                <input type="number" id="page-input" min="0"
                       class="px-1 py-2 rounded-lg text-black font-semibold text-center w-20"
                       placeholder="1, 2, ..."/>
                <div class="flex-1 flex justify-center">
                    <div id="pagination-controls" class="flex flex-wrap gap-2 justify-center"></div>
                </div>
                <div class="flex items-center mr-8">
                    <input type="search" id="AsteroidFilter" name="AsteroidFilter"
                           class=" transform translate-x-8 text-black text-sm p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm w-60 placeholder:text-sky-700 ml-8"
                           placeholder="Cerca Asteroide con ID/nome">
                </div>
            </div>

        </div>


        <!-- Filtri -->
        <div
            class="bg-white opacity-85 px-6 py-4 rounded-2xl border-black border-2 shadow-black shadow-2xl max-w-lg mx-auto mr-4">
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

            <form id="formConLoader" action="{{route('sentry.search')}}" method="POST" class="w-full max-w-7xl rounded-lg text-black items-center">
                @csrf  {{-- Token CSRF per sicurezza --}}


                <!-- Data Minima -->
                <div class="mt-4 mb-2 flex col-span-2 gap-2 text-center flex items-center">
                    <label for="date-min" class="block text-sm font-medium text-gray-700">Data Minima</label>
                    <input type="date" id="date-min" name="date-min"
                           min="{{ \Carbon\Carbon::now()->toDateString() }}"
                           class="mt-1 block px-4 py-2 text-sm border-gray-300 rounded-md shadow-sm" />
                </div>

                <!-- Data Massima -->
                <div class="mb-4 flex col-span-2 gap-2 text-center flex items-center">
                    <label for="date-max" class="block text-sm font-medium text-gray-700">Data Massima</label>
                    <input type="date" id="date-max" name="date-max"
                           class="mt-1 block px-4 py-2 text-sm border-gray-300 rounded-md shadow-sm" />
                </div>

                <!-- Diametro Minimo -->
                <div class="mb-2 flex col-span-2 gap-2">
                    <label for="diam-min" class="block text-sm font-medium text-gray-700 text-center items-center flex">Diametro Minimo</label>
                    <input type="number" id="diam-min" name="diam-min" min="0" class="mt-1 w-24 block px-4 py-2 text-sm border-gray-300 rounded-md shadow-sm" />
                </div>

                <!-- Probabilità -->
                <div class="mt-3 mb-5 items-center">
                    <label for="ip-min" class="block text-sm font-medium text-gray-700 text-center items-center flex">Probabilità di Impatto Minima (%)</label>
                    <input type="number" id="ip-min" name="ip-min" min="0" step="0.0000000001" max="100" class="flex items-center w-full mt-1 block px-4 py-2 text-sm border-gray-300 rounded-md shadow-sm" />
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

    <!-- Modal for applied filters -->
    <div id="filter-modal" class="hidden fixed top-0 left-0 w-full h-full bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white px-6 pt-2 pb-1 rounded-2xl border-black border-2 shadow-black shadow-2xl">
            <div class="flex flex-col items-center">
                <h3 class="font-extrabold mb-4 text-[18px] underline">Filtri Applicati</h3>
                <ul class="text-[16px]">
                    <li><strong>Data:</strong>
                        {{ request()->input('date-min') ? request()->input('date-min') : 'N/A' }} -
                        {{ request()->input('date-max') ? request()->input('date-max') : 'N/A' }}
                    </li>

                    <li><strong>Diametro Minimo:</strong>
                        {{ request()->input('diam-min') ? request()->input('diam-min') : 'N/A' }}
                    </li>

                    <li><strong>Probabilità di Impatto Minima (%):</strong>
                        {{ request()->input('ip-min') ? request()->input('ip-min') : 'N/A' }}
                    </li>
                </ul>
            </div>
            <button id="close-filter-modal" class="mt-4 ml-20 bg-red-500 text-white px-4 py-2 rounded-lg">Chiudi</button>
        </div>
    </div>


    <!-- Toast Notification -->
    @include('components.toastNotification')

    <!-- Loader -->
    @include('components.loader')

</main>

</body>

{{-- @include('layouts.footer') --}}
</html>

<style>
    .highlight-selected {
        background-color: #cbd5e1 !important; /* bg-blue-200 */
    }
</style>

<script>
    /*** Info Aggiuntive **/
    document.querySelectorAll('.asteroid-radio').forEach(radio => {
        radio.addEventListener('change', () => {
            const des = radio.dataset.des;
            const id = radio.dataset.id;
            const link1 = radio.dataset.link1;
            const link2 = radio.dataset.link2;
            const link3 = radio.dataset.link3;

            document.getElementById('asteroid-name').innerHTML = `${des}<br>[ID: ${id}]`;
            document.getElementById('link-stats').href = link1;
            document.getElementById('link-stats').textContent = 'Clicca qui per visualizzare dati Statistici su precedenti incontri ravvicinati.';
            document.getElementById('link-impact').href = link2;
            /*document.getElementById('link-impact').innerHTML = `
                Clicca qui per visualizzare dati Tecnici sul Possibile Impatto Futuro.
                <span style="display: block; font-size: 0.90rem; color: black; text-decoration: none; font-style: italic; line-height: 1;">(Solo per Autenticati)</span>
            `;*/
            document.getElementById('link-impact').innerHTML = `
                Clicca qui per visualizzare dati Tecnici sul Possibile Impatto Futuro.
            `;
            document.getElementById('link-nasa').href = link3;
            document.getElementById('link-nasa').textContent = 'Clicca qui per vedere cosa ha da dire la Nasa a riguardo.';
        });
    });

    const rows = document.querySelectorAll('.asteroid-row');
    document.querySelectorAll('.asteroid-radio').forEach(radio => {
        radio.addEventListener('change', () => {
            rows.forEach(row => row.classList.remove('highlight-selected'));
            radio.closest('tr').classList.add('highlight-selected');
        });
    });
</script>



<script>
    /*** Tabella **/
    document.addEventListener('DOMContentLoaded', function () {
        const table = document.getElementById('asteroid-table');
        const allRows = Array.from(document.querySelectorAll('.asteroid-row'));
        let filteredRows = [...allRows];
        const perPage = 5;
        let currentPage = 1;
        let sortDirection = {};
        const pageInput = document.getElementById('page-input');
        const filterInput = document.getElementById('AsteroidFilter');

        pageInput.addEventListener('input', function () {
            const pageNumber = parseInt(pageInput.value);
            const totalPages = Math.ceil(filteredRows.length / perPage);
            if (pageNumber >= 1 && pageNumber <= totalPages) {
                currentPage = pageNumber;
                showPage(currentPage);
            }
        });

        filterInput.addEventListener('input', function () {
            const searchText = this.value.toLowerCase().trim();
            filteredRows = allRows.filter(row => {
                const id = row.children[0]?.innerText.toLowerCase() || "";
                const name = row.children[1]?.innerText.toLowerCase() || "";
                return id.includes(searchText) || name.includes(searchText);
            });
            currentPage = 1;
            showPage(currentPage);
        });

        function updateRowColors() {
            let visibleRows = filteredRows.filter(row => row.style.display !== 'none');
            visibleRows.forEach((row, i) => {
                row.classList.remove('bg-white', 'bg-blue-50');
                row.classList.add(i % 2 === 0 ? 'bg-white' : 'bg-blue-50');
            });
        }

        function replaceUnavailableText() {
            allRows.forEach(row => {
                Array.from(row.children).forEach(cell => {
                    const text = cell.textContent.trim();
                    if (["0.00", "...", "0.00 m", "0.00 km/s"].includes(text)) {
                        cell.textContent = "Non Disponibile";
                    }
                });
            });
        }

        function showPage(page) {
            const totalPages = Math.ceil(filteredRows.length / perPage);
            const start = (page - 1) * perPage;
            const end = start + perPage;
            allRows.forEach(row => row.style.display = 'none');
            filteredRows.forEach((row, index) => {
                row.style.display = (index >= start && index < end) ? '' : 'none';
            });
            updateRowColors();
            renderPaginationControls(page, totalPages);
        }

        function renderPaginationControls(activePage, totalPages) {
            const container = document.getElementById('pagination-controls');
            container.innerHTML = '';

            const windowSize = 4;
            const windowStart = Math.max(2, activePage - Math.floor(windowSize / 2));
            const windowEnd = Math.min(totalPages - 1, windowStart + windowSize - 1);

            container.appendChild(createPageButton(1, activePage));

            if (windowStart > 2) {
                container.appendChild(createEllipsis());
            }

            for (let i = windowStart; i <= windowEnd; i++) {
                container.appendChild(createPageButton(i, activePage));
            }

            if (windowEnd < totalPages - 1) {
                container.appendChild(createEllipsis());
            }

            if (totalPages > 1) {
                container.appendChild(createPageButton(totalPages, activePage));
            }
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

        function createEllipsis() {
            const ellipsis = document.createElement('span');
            ellipsis.className = 'text-white px-2 py-2 text-[16px] font-bold';
            ellipsis.innerText = '...';
            return ellipsis;
        }

        function sortTableByColumn(columnIndex) {
            const direction = sortDirection[columnIndex] === 'asc' ? 'desc' : 'asc';
            sortDirection[columnIndex] = direction;

            filteredRows.sort((a, b) => {
                const aCell = a.children[columnIndex].innerText.trim();
                const bCell = b.children[columnIndex].innerText.trim();

                if (columnIndex === 0) {
                    return direction === 'asc'
                        ? new Date(aCell) - new Date(bCell)
                        : new Date(bCell) - new Date(aCell);
                }

                if ([1, 2, 3, 4, 5].includes(columnIndex)) {
                    const aVal = parseFloat(aCell.replace(/[^\d.-]/g, '')) || 0;
                    const bVal = parseFloat(bCell.replace(/[^\d.-]/g, '')) || 0;
                    return direction === 'asc' ? aVal - bVal : bVal - aVal;
                }

                return direction === 'asc'
                    ? aCell.localeCompare(bCell)
                    : bCell.localeCompare(aCell);
            });

            const tbody = table.querySelector('tbody');
            filteredRows.forEach(row => tbody.appendChild(row));
            showPage(1);
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


<script>
    const dateMinInput = document.getElementById('date-min');
    const dateMaxInput = document.getElementById('date-max');

    // Quando cambia la data minima
    dateMinInput.addEventListener('change', function () {
        const minDate = this.value;
        dateMaxInput.min = minDate; // Imposta il minimo valido su date-max

        if (dateMaxInput.value && dateMaxInput.value < minDate) {
            alert("La data massima è precedente alla nuova data minima. È stata azzerata.");
            dateMaxInput.value = '';
        }
    });

    // Quando cambia la data massima
    dateMaxInput.addEventListener('change', function () {
        const maxDate = this.value;
        const minDate = dateMinInput.value;

        if (minDate && maxDate < minDate) {
            alert("La data massima non può essere precedente alla data minima. È stata azzerata.");
            this.value = '';
        }
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const showFiltersButton = document.getElementById('show-filters');
        const filterModal = document.getElementById('filter-modal');
        const closeModalButton = document.getElementById('close-filter-modal');

        showFiltersButton.addEventListener('click', function () {
            filterModal.classList.remove('hidden');
        });

        closeModalButton.addEventListener('click', function () {
            filterModal.classList.add('hidden');
        });

        filterModal.addEventListener('click', function (event) {
            if (event.target === filterModal) {
                filterModal.classList.add('hidden');
            }
        });
    });

</script>
