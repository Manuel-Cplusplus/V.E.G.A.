{{--
    V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
    Copyright (c) 2025 Manuel Carlucci

    Licensed under CC BY-NC-SA 4.0: http://creativecommons.org/licenses/by-nc-sa/4.0/
--}}

<div id="CADChartContent" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white p-6 rounded-xl w-5/6 h-5/6 relative flex flex-col items-center text-black">

        <h3 class="text-xl font-bold mb-4">Grafico relativo agli Incontri Ravvicinati con la Terra</h3>

        <!-- Contenitore del Grafico -->
        <div class="w-full h-full flex items-center justify-center">
            <canvas id="CADChart"></canvas>
        </div>

        <!-- Bottone Chiudi -->
        <button onclick="closeCADChart()" class=" bottom-2 right-1/2 translate-x-1/2 bg-red-500 text-white p-2 rounded-full">
            Chiudi
        </button>
    </div>
</div>

<!-- Dati per visualizzazione LLM in un div nascosto -->
<div id="chart-data" style="display:none;">

</div>


<script>
    function openCADChart() {
        document.getElementById("CADChartContent").classList.remove("hidden");
    }

    function closeCADChart() {
        document.getElementById("CADChartContent").classList.add("hidden");
    }

    // Inizializza il grafico quando la pagina è caricata
    document.addEventListener('DOMContentLoaded', function() {
        initializeCADChart();
    });
</script>


<script>
    // Script per la visualizzazione del grafico delle miss distance
    function initializeCADChart() {
        // Recupera il contesto del canvas
        const ctx = document.getElementById('CADChart').getContext('2d');

        /** Recupero Dati **/
        const storedData = localStorage.getItem('detailedAsteroidData');
        if (!storedData) {
            console.warn("Nessun dato trovato nel localStorage per 'detailedAsteroidData'");
            return;
        }

        const asteroidData = JSON.parse(storedData);
        const datasets = [];
        const colors = [
            'rgba(255, 99, 132, 1)',    // rosso
            'rgba(54, 162, 235, 1)',    // blu
            'rgba(255, 206, 86, 1)',    // giallo
            'rgba(0, 255, 74, 1)',      // verde
            'rgba(153, 102, 255, 1)',   // viola
        ];

        let colorIndex = 0;

        // Preparazione dei dati per ogni asteroide
        for (const asteroidId in asteroidData) {
            if (asteroidData.hasOwnProperty(asteroidId)) {
                const asteroid = asteroidData[asteroidId];
                const name = asteroid.asteroidData?.name || `Asteroide ${asteroidId}`;

                // Verifica che esistano i dati di approccio ravvicinato per questo asteroide
                if (asteroid.earthDates && asteroid.earthMissDistances &&
                    asteroid.earthDates.length === asteroid.earthMissDistances.length) {

                    const dataPoints = [];

                    // Preparazione dei punti dati per questo asteroide
                    for (let i = 0; i < asteroid.earthDates.length; i++) {
                        dataPoints.push({
                            x: asteroid.earthDates[i],
                            y: parseFloat(asteroid.earthMissDistances[i])
                        });
                    }

                    // Ordina i punti per data
                    dataPoints.sort((a, b) => new Date(a.x) - new Date(b.x));

                    // Crea il dataset per questo asteroide
                    datasets.push({
                        label: name,
                        data: dataPoints,
                        borderColor: colors[colorIndex % colors.length],
                        backgroundColor: colors[colorIndex % colors.length].replace('1)', '0.2)'),
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        fill: false,
                        tension: 0.1,
                        spanGaps: true
                    });

                    colorIndex++;
                } else {
                    console.warn(`Dati di approccio ravvicinato mancanti o incompleti per l'asteroide ${name} (${asteroidId})`);
                }
            }
        }

        // Creazione del grafico
        const cadChart = new Chart(ctx, {
            type: 'line',
            data: {
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'year',
                            displayFormats: {
                                year: 'yyyy'
                            }
                        },
                        title: {
                            display: true,
                            text: 'Data'
                        }
                    },
                    y: {
                        type: 'logarithmic', // Scala logaritmica per visualizzare le diverse distanze più facilmente
                        title: {
                            display: true,
                            text: 'Miss Distance (km)'
                        },
                        ticks: {
                            callback: function(value) {
                                return formatDistance(value);
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const date = new Date(context.parsed.x).toLocaleDateString();
                                return `${context.dataset.label}: ${formatDistance(context.parsed.y)} km (${date})`;
                            }
                        }
                    },
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Distanze di Approccio degli Asteroidi alla Terra'
                    }
                }
            }
        });

        // Esponi il grafico globalmente se necessario per successive modifiche
        window.cadChart = cadChart;
    }

    // Funzione per formattare le distanze in un formato più leggibile
    function formatDistance(distance) {
        if (distance === null || distance === undefined) return 'N/A';

        // Converti a numero se è una stringa
        const num = typeof distance === 'string' ? parseFloat(distance) : distance;

        return num.toFixed(2);
    }

</script>
