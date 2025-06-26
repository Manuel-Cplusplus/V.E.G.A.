{{--
    V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
    Copyright (c) 2025 Manuel Carlucci

    Licensed under CC BY-NC-SA 4.0: http://creativecommons.org/licenses/by-nc-sa/4.0/
--}}

<div id="EnergyChartContent" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white p-6 rounded-xl w-5/6 h-5/6 relative flex flex-col items-center">

        <h3 class="text-xl font-bold mb-4">Grafico relativo all' Energia di Impatto</h3>

        <!-- Contenitore del Grafico -->
        <div class="w-full h-full flex items-center justify-center">
            <canvas id="energyChart"></canvas>
        </div>

        <!-- Bottone Chiudi -->
        <button onclick="closeEnergyChart()" class=" bottom-2 right-1/2 translate-x-1/2 bg-red-500 text-white p-2 rounded-full">
            Chiudi
        </button>
    </div>
</div>

<!-- Dati per visualizzazione LLM in un div nascosto -->
<div id="chart-data" style="display:none;">
    <pre id="llm-json">
        {!! json_encode([
            'energyByYear' => $energyByYear
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
    </pre>
</div>


<script>
    function openEnergyChart() {
        document.getElementById("EnergyChartContent").classList.remove("hidden");
    }

    function closeEnergyChart() {
        document.getElementById("EnergyChartContent").classList.add("hidden");
    }
</script>

<script>
    // Dati provenienti dal controller
    const energyByYear = @json($energyByYear);

    // Estrai gli anni e i valori massimi, medi e minimi
    const years = Object.keys(energyByYear);
    const maxValues = years.map(year => energyByYear[year].max);
    const avgValues = years.map(year => energyByYear[year].avg);
    const minValues = years.map(year => energyByYear[year].min);


    const ctx = document.getElementById('energyChart').getContext('2d');
    const energyChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: years, // Anni sull'asse delle X
            datasets: [
                {
                    label: 'Impatto Massimo (kt)',
                    data: maxValues, // Sulle y
                    borderColor: 'red',
                    backgroundColor: 'rgba(255, 0, 0, 0.2)',
                    fill: false,
                    tension: 0.1
                },
                {
                    label: 'Impatto Medio (kt)',
                    data: avgValues,
                    borderColor: 'cyan',
                    backgroundColor: 'rgba(173, 216, 230, 0.2)',
                    fill: false,
                    tension: 0.1
                },
                {
                    label: 'Impatto Minimo (kt)',
                    data: minValues,
                    borderColor: 'green',
                    backgroundColor: 'rgba(0, 255, 0, 0.2)',
                    fill: false,
                    tension: 0.1
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        // Funzione di callback per il contenuto del tooltip
                        title: function(tooltipItem) {
                            const year = tooltipItem[0].label;
                            return `Anno: ${year}`;
                        },
                        label: function(tooltipItem) {
                            const year = tooltipItem.label;
                            const data = energyByYear[year];
                            return [
                                `Impatto Massimo: ${data.max} kT`,
                                `Impatto Minimo: ${data.min} kT`,
                                `Impatto Medio: ${data.avg.toFixed(2)} kT`,
                                `Numero di Impatti: ${data.count}`
                            ];
                        }
                    }
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Anno'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Impatto (kt)'
                    },
                    beginAtZero: true
                }
            }
        }
    });
</script>


