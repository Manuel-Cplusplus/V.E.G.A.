{{--
    V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
    Copyright (c) 2025 Manuel Carlucci

    Licensed under CC BY-NC-SA 4.0: http://creativecommons.org/licenses/by-nc-sa/4.0/
--}}

<div id="ImpactChartContent" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white p-6 rounded-xl w-5/6 h-5/6 relative flex flex-col items-center text-black">

        <h3 class="text-xl font-bold mb-4">Grafico relativo agli Impatti Futuri</h3>

        <!-- Contenitore del Grafico -->
        <div class="w-full h-full flex items-center justify-center">
            <canvas id="impactChart"></canvas>
        </div>

        <!-- Bottone Chiudi -->
        <button onclick="closeImpactChart()" class="bottom-2 right-1/2 translate-x-1/2 bg-red-500 text-white p-2 rounded-full">
            Chiudi
        </button>
    </div>
</div>

<!-- Dati per visualizzazione LLM in un div nascosto -->
<div id="chart-data" style="display:none;">

</div>


<script>
    function openImpactChart() {
        document.getElementById("ImpactChartContent").classList.remove("hidden");
    }

    function closeImpactChart() {
        document.getElementById("ImpactChartContent").classList.add("hidden");
    }
</script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Adapter date-fns -->
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@3"></script>
<!-- Plugin Chart.js Datalabels -->
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        /** Recupero Dati **/
        const storedData = localStorage.getItem('detailedAsteroidData');
        if (!storedData) {
            console.warn("Nessun dato trovato nel localStorage per 'detailedAsteroidData'");
            return;
        }

        const asteroidData = JSON.parse(storedData);
        let impactPoints = [];

        for (const asteroidId in asteroidData) {
            if (asteroidData.hasOwnProperty(asteroidId)) {
                const entry = asteroidData[asteroidId];
                const name = entry.asteroidData?.name ?? "Sconosciuto";

                const sentry = entry.sentrySummary;
                if (sentry && sentry.pdate && sentry.energy) {
                    impactPoints.push({
                        x: new Date(sentry.pdate),
                        y: parseFloat(sentry.energy),
                        label: name
                    });
                } else {
                    console.warn(`Dati Sentry mancanti per l'asteroide ${name} (${asteroidId})`);
                }
            }
        }

        impactPoints.sort((a, b) => a.x - b.x);
        Chart.defaults.font.size = 14;

        // Calcola i valori minimi e massimi per gli assi
        let minDate = impactPoints.length > 0 ? impactPoints[0].x : new Date();
        let maxDate = impactPoints.length > 0 ? impactPoints[impactPoints.length - 1].x : new Date();
        let minEnergy = impactPoints.length > 0 ? Math.min(...impactPoints.map(p => p.y)) : 0;
        let maxEnergy = impactPoints.length > 0 ? Math.max(...impactPoints.map(p => p.y)) : 10;

        // Aggiungi un po' di margine
        minEnergy = minEnergy * 0.9;
        maxEnergy = maxEnergy * 1.1;

        // Aggiungi margine alle date (15% in più prima e dopo)
        const dateRange = maxDate - minDate;
        minDate = new Date(minDate.getTime() - dateRange * 0.05);
        maxDate = new Date(maxDate.getTime() + dateRange * 0.05);

        // Funzione per generare le date intermedie da mostrare sull'asse x
        function generateDateTicks(min, max) {
            const ticks = [];
            const totalDays = (max - min) / (1000 * 60 * 60 * 24);

            // Scegli la granularità appropriata in base alla durata totale
            let step;
            if (totalDays > 365 * 2) { // Più di 2 anni
                step = 180; // 6 mesi
            } else if (totalDays > 365) { // Più di 1 anno
                step = 90; // 3 mesi
            } else if (totalDays > 180) { // Più di 6 mesi
                step = 30; // 1 mese
            } else {
                step = 15; // 15 giorni
            }

            let current = new Date(min);
            while (current <= max) {
                ticks.push(new Date(current));
                current.setDate(current.getDate() + step);
            }

            return ticks;
        }

        // Genera le date intermedie
        const dateTicks = generateDateTicks(minDate, maxDate);

        const ctx = document.getElementById("impactChart").getContext("2d");

        new Chart(ctx, {
            type: 'scatter',
            data: {
                datasets: [{
                    label: "Energia Impatti Futuri",
                    data: impactPoints,
                    fill: false,
                    borderColor: 'blue',
                    backgroundColor: 'blue',
                    showLine: true,
                    tension: 0.5,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            tooltipFormat: 'PPpp',
                            unit: 'month',
                            displayFormats: {
                                month: 'MMM yyyy'
                            }
                        },
                        title: {
                            display: true,
                            text: 'Data di impatto prevista',
                            font: {
                                size: 16,
                                weight: 'bold'
                            }
                        },
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45,
                            font: {
                                size: 12
                            },
                            // Mostra date effettive con formato specifico
                            callback: function(value, index, values) {
                                const date = new Date(value);
                                // Formato italiano per le date (giorno/mese/anno)
                                return date.toLocaleDateString('it-IT', {
                                    day: '2-digit',
                                    month: '2-digit',
                                    year: '2-digit'
                                });
                            },
                            // Mostra più date per avere una scala più dettagliata
                            autoSkip: false,
                            maxTicksLimit: 10
                        },
                        // Imposta min, max e ticks definiti per asse x
                        min: minDate,
                        max: maxDate,
                        ticks: {
                            source: 'data',
                            maxRotation: 45,
                            minRotation: 45,
                            font: {
                                size: 12
                            },
                            // Mostra date effettive con formato specifico
                            callback: function(value, index, values) {
                                const date = new Date(value);
                                // Formato italiano per le date (giorno/mese/anno)
                                return date.toLocaleDateString('it-IT', {
                                    day: '2-digit',
                                    month: '2-digit',
                                    year: '2-digit'
                                });
                            }
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Energia (Mt)',
                            font: {
                                size: 16,
                                weight: 'bold'
                            }
                        },
                        // Imposta min e max per asse y
                        suggestedMin: minEnergy,
                        suggestedMax: maxEnergy,
                        ticks: {
                            font: {
                                size: 12
                            },
                            // Mostra valori effettivi con il formato corretto
                            callback: function(value) {
                                return value.toFixed(1) + ' Mt';
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const point = impactPoints[context.dataIndex];
                                const date = new Date(point.x).toLocaleDateString('it-IT', {
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric'
                                });
                                return [
                                    `Asteroide: ${point.label}`,
                                    `Data: ${date}`,
                                    `Energia: ${point.y.toFixed(2)} Mt`
                                ];
                            }
                        },
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFont: {
                            size: 14
                        },
                        bodyFont: {
                            size: 14
                        },
                        padding: 10
                    },
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    },
                    datalabels: {
                        color: '#000',
                        align: 'top',
                        offset: 10,
                        font: {
                            weight: 'bold',
                            size: 12
                        },
                        formatter: function (value) {
                            // Formato compatto per le etichette dei punti
                            const date = new Date(value.x).toLocaleDateString('it-IT', {
                                year: 'numeric',
                                month: '2-digit',
                                day: '2-digit'
                            });
                            return `${value.label}\n${date}\n${value.y.toFixed(1)} Mt`;
                        },
                        // Mostra le etichette per TUTTI i punti
                        display: true,
                        backgroundColor: 'rgba(255, 255, 255, 0.7)',
                        borderRadius: 4,
                        padding: 4
                    }
                },
                interaction: {
                    mode: 'nearest',
                    intersect: true
                },
                elements: {
                    line: { tension: 0.5 }
                }
            },
            plugins: [ChartDataLabels]
        });

    });
</script>
