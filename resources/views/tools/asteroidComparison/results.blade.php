{{--
    V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
    Copyright (c) 2025 Manuel Carlucci

    Licensed under CC BY-NC-SA 4.0: http://creativecommons.org/licenses/by-nc-sa/4.0/
--}}

    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('layouts.head')

<body class="antialiased min-h-screen">

@include('layouts.header')

<main class="flex flex-col items-center justify-center overflow-hidden text-white px-2 py-4">
    <section class="text-center">

        <h2 class="text-2xl font-bold text-green-400 flex items-center">
            <span class="flex-grow h-0.5 bg-white mr-2"></span>
            &#10022; Confronto Asteroidi - Risultato &#10022;
            <span class="flex-grow h-0.5 bg-white ml-2"></span>
        </h2>
        <p class="text-sm sm:text-base leading-relaxed shadow-text-white">
            Questa sezione consente di confrontare dati basilari di più asteroidi. <br>
            Potrai Selezionare un asteroide fra quelli salvati fra i preferiti oppure cercarlo tramite denominazione.
        </p>
    </section>

    <!-- Sezione Grafico + Tabella + Grafico -->
    <div class="w-screen flex flex-col lg:flex-row justify-center gap-2 mt-4">

        <!-- Grafico PHA (Sinistra) -->
        <div class="ml-5 flex flex-col items-center bg-opacity-80 bg-white p-4 mx-2 rounded-xl border-black border-2 shadow-2xl shadow-black text-black w-full lg:w-[16%]">
            <h2 class="text-lg font-semibold mb-4 text-center">Grafico Pericolosità</h2>
            <canvas id="hazardChart" class="max-w-[160px]"></canvas>
            <div class="mt-6 text-[13px] flex flex-col gap-2 text-center">
                <div>
                    <span class="inline-block w-4 h-4 rounded-full bg-yellow-500 mr-2"></span>
                    <span id="hazardous-count"></span>
                </div>
                <div>
                    <span class="inline-block w-4 h-4 rounded-full bg-blue-500 mr-2"></span>
                    <span id="non-hazardous-count"></span>
                </div>
            </div>
        </div>

        <!-- Tabella (Centro) -->
        <div class="overflow-x-auto w-full">
            <table id="asteroid-table" class="table-auto border-collapse w-full text-center shadow-lg rounded-lg overflow-hidden text-black">
                <thead>
                <tr class="bg-blue-100 text-blue-900">
                    <th class="text-[14px] px-4 py-2 whitespace-nowrap">Asteroide</th>
                    <th class="text-[14px] px-4 py-2 whitespace-nowrap">Diametro</th>
                    <th class="text-[14px] px-4 py-2 whitespace-nowrap">Massa (kg)</th>
                    <th class="text-[14px] px-4 py-2 whitespace-nowrap">Velocità (km/s)</th>
                    <th class="text-[14px] px-4 py-2 whitespace-nowrap">Pericoloso</th>
                    <th class="text-[14px] px-4 py-2 whitespace-nowrap">Rischio Impatto</th>
                    <th class="text-[14px] px-4 py-2 whitespace-nowrap">Prob. Impatto</th>
                    <th class="text-[14px] px-4 py-2 whitespace-nowrap">Energia Impatto</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <!-- Grafico Sentry (Destra) -->
        <div class="mr-5 flex flex-col items-center bg-opacity-80 bg-white p-4 mx-2 rounded-xl border-black border-2 shadow-2xl shadow-black text-black w-full lg:w-[16%]">
            <h2 class="text-lg font-semibold mb-4 text-center">Grafico Oggetti a Rischio Impatto</h2>
            <canvas id="sentryChart" class="max-w-[160px]"></canvas>
            <div class="mt-6 text-[13px] grid grid-cols-1 gap-2 text-center">
                <div>
                    <span class="inline-block w-4 h-4 rounded-full bg-red-500 mr-2"></span>
                    <span id="sentry-count"></span>
                </div>
                <div>
                    <span class="inline-block w-4 h-4 rounded-full bg-green-500 mr-2"></span>
                    <span id="non-sentry-count"></span>
                </div>
            </div>
        </div>

    </div>


    <!-- Sezione Grafici -->
    <div class="flex flex-row gap-20 mt-80 transform translate-y-4 fixed ">
        <!-- Pulsante Grafico 1 -->
        <div class="flex items-center justify-items-center">
            <button onclick="openCADChart()"
                    class="opacity-90 bg-yellow-500 hover:bg-orange-400 text-black px-10 py-2 rounded-2xl flex flex-col items-center gap-2 border-black border-2 relative">
                <span> Visualizza Grafico <br> relativo agli Incontri Ravvicinati </span>
                <img src="{{ asset('media/icons/distanza.png') }}" alt="Grafico Incontri Ravvicinati"
                         class="w-14 h-14 -mt-2">
            </button>
        </div>


        <!-- Pulsante Grafico 2 -->
        <div class="flex items-center justify-items-center">
            <button onclick="openImpactChart()"
                    class="opacity-90 bg-yellow-500 hover:bg-orange-400 text-black px-10 py-2 rounded-2xl flex flex-col items-center gap-2 border-black border-2 relative">
                <span> Visualizza Grafico <br> relativo agli Impatti Futuri </span>
                <img src="{{ asset('media/icons/impatto_asteroide_nero.png') }}" alt="Grafico Impatti Futuri"
                     class="w-10 h-10 ">
            </button>
        </div>


        <!-- Pulsante Grafico 3 -->
        <div class="flex items-center justify-items-center">
            <button onclick="openDimensionChart()"
                    class="opacity-90 bg-yellow-500 hover:bg-orange-400 text-black px-10 py-2 rounded-2xl flex flex-col items-center gap-2 border-black border-2 relative">
                <span> Visualizza <br> Confronto Dimensioni </span>
                <img src="{{ asset('media/icons/dimensioni.png') }}" alt="Grafico Confronto Dimensioni"
                     class="w-12 h-12 -mt-2">
            </button>
        </div>
    </div>

    <!-- Grafici -->
    @include('tools.asteroidComparison.components.CADChart')
    @include('tools.asteroidComparison.components.impactChart')
    @include('tools.asteroidComparison.components.dimensionComparison')

    @include('components.toastNotification')
    @include('tools.generalTools.distanceVisualization')
</main>

</body>
</html>

<script>
    /** Riempimento Tabella Dinamicamente e Grafici a Torta **/
    document.addEventListener("DOMContentLoaded", function () {
        const detailedData = JSON.parse(localStorage.getItem('detailedAsteroidData')) || {};
        const selectedAsteroids = JSON.parse(localStorage.getItem('selectedAsteroids')) || {};
        const tableBody = document.querySelector("#asteroid-table tbody");

        //console.log(selectedAsteroids);

        let hazardousCount = 0;
        let nonHazardousCount = 0;
        let sentryCount = 0;
        let nonSentryCount = 0;

        Object.values(detailedData).forEach((entry, index) => {
            const asteroid = entry.asteroidData || {};
            const sentry = entry.sentrySummary || {};

            const row = document.createElement("tr");
            row.classList.add(index % 2 === 0 ? "bg-white" : "bg-blue-50", "border-b");

            const createCell = (value, className = "") => {
                const cell = document.createElement("td");
                cell.className = `px-4 py-2 text-[13px] whitespace-nowrap ${className}`;
                cell.textContent = value ?? "N/D";
                return cell;
            };

            const formatMass = (massStr) => {
                const massNumber = parseFloat(massStr.replaceAll(',', ''));
                return isNaN(massNumber) ? "N/D" : Math.round(massNumber).toLocaleString('it-IT');
            };

            const selectedData = selectedAsteroids.find(a => a.id === asteroid.id);
            const mass = selectedData?.mass ? formatMass(selectedData.mass) : "N/D";

            row.appendChild(createCell(asteroid.name || asteroid.designation));
            row.appendChild(createCell(asteroid.diameter ? `${parseFloat(asteroid.diameter).toFixed(2)} m` : "N/D"));
            row.appendChild(createCell(mass)); // Ora la massa è correttamente recuperata
            row.appendChild(createCell(asteroid.velocity_km_s ? `${asteroid.velocity_km_s} km/s` : "N/D"));


            const isHazardous = asteroid.is_hazardous;
            row.appendChild(createCell(isHazardous ? "Sì" : "No", isHazardous ? "text-red-600 font-bold" : "text-green-600 font-bold"));

            const isSentry = asteroid.is_sentry_object;
            row.appendChild(createCell(isSentry ? "Sì" : "No", isSentry ? "text-red-600 font-bold" : "text-green-600 font-bold"));

            let probCell;
            if (sentry.ip) {
                probCell = createCell(`1 su ${Math.round(1 / sentry.ip)}`, "text-blue-600 underline cursor-pointer");
                probCell.title = `Probabilità esatta: ${sentry.ip}`;
            } else {
                probCell = createCell("N/D");
            }
            row.appendChild(probCell);

            row.appendChild(probCell);

            row.appendChild(createCell(sentry.energy ? `${(sentry.energy * 1000)} kT` : "N/D"));

            tableBody.appendChild(row);

            if (asteroid.is_hazardous) hazardousCount++; else nonHazardousCount++;
            if (asteroid.is_sentry_object) sentryCount++; else nonSentryCount++;
        });

        document.getElementById("hazardous-count").textContent = hazardousCount + ' record trovati - Classificato come pericoloso';
        document.getElementById("non-hazardous-count").textContent = nonHazardousCount + ' record trovati - Non classificato come pericoloso';
        document.getElementById("sentry-count").textContent = sentryCount + ' record trovati - Oggetti con rischio di impatto (sentry)';
        document.getElementById("non-sentry-count").textContent = nonSentryCount + ' record trovati - Nessun rischio di impatto';

        new Chart(document.getElementById('hazardChart'), {
            type: 'pie',
            data: {
                labels: ['Pericolosi', 'Non pericolosi'],
                datasets: [{
                    data: [hazardousCount, nonHazardousCount],
                    backgroundColor: ['#eab308', '#3b82f6'],
                    borderColor: ['#000', '#000'],
                    borderWidth: 1.5
                }]
            },
            options: {
                responsive: true,
                cutout: '50%',
                plugins: {
                    legend: { display: false },
                    title: { display: false }
                }
            }
        });

        new Chart(document.getElementById('sentryChart'), {
            type: 'pie',
            data: {
                labels: ['A rischio impatto', 'Nessun rischio'],
                datasets: [{
                    data: [sentryCount, nonSentryCount],
                    backgroundColor: ['#ef4444', '#10b981'],
                    borderColor: '#000',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                cutout: '50%',
                plugins: {
                    legend: { display: false },
                    title: { display: false }
                }
            }
        });
    });
</script>
