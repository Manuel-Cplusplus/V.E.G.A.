{{--
    V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
    Copyright (c) 2025 Manuel Carlucci

    Licensed under CC BY-NC-SA 4.0: http://creativecommons.org/licenses/by-nc-sa/4.0/
--}}

<!-- Grafico Incontri Ravvicinati -->
<div class="bg-white opacity-75 px-6 py-4 rounded-2xl border-black border-2 shadow-black shadow-2xl w-screen max-w-md">
    <h2 class="font-extrabold text-[18px] underline text-center mb-2">Incontri Ravvicinati</h2>
    <!-- Icona per ingrandire -->
    <button onclick="openChartPopup()" class="absolute p-2 bg-blue-500 text-white rounded-full transform translate-x-80 -translate-y-9">
        <!-- Icona di ingrandimento -->
        <i class="fas fa-search-plus"></i>
    </button>
    <div class="w-full h-[250px]">
        <canvas id="closeApproachChart"></canvas>
    </div>
</div>

<!-- Pop-up per ingrandire il grafico -->
<div id="chartPopup" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white p-6 rounded-xl w-5/6 h-5/6 relative flex flex-col items-center">

        <!-- Contenitore del Grafico -->
        <div class="w-full h-full flex items-center justify-center">
            <canvas id="expandedCloseApproachChart"></canvas>
        </div>

        <!-- Bottone Chiudi -->
        <button onclick="closeChartPopup()" class="bottom-2 right-1/2 translate-x-1/2 bg-red-500 text-white p-2 rounded-full">
            Chiudi
        </button>
    </div>
</div>


    {{-- Dati per visualizzazione LLM in un div nascosto --}}
    <div id="chart-data" style="display:none;">
        <pre id="llm-json">
            {!! json_encode([
                'dates' => $dates,
                'missDistances' => $missDistances,
                'closeApproachData' => $closeApproachData,
                'earthDates' => $earthDates,
                'earthMissDistances' => $earthMissDistances
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
        </pre>
    </div>


<script>
    /*** Grafico ***/
    document.addEventListener("DOMContentLoaded", function () {
        const dates = @json($dates);
        const missDistances = @json($missDistances).map(Number);
        const closeApproachData = @json($closeApproachData);

        const earthDates = @json($earthDates);
        const earthMissDistances = @json($earthMissDistances).map(Number);

        // Verifica se ci sono dati per la Terra - se non ci sono non stamperà la linea per la terra
        const hasEarthData = earthDates && earthDates.length > 0 && earthMissDistances && earthMissDistances.length > 0;

        // Limita a 50 punti visibili
        const trimmedDates = dates.slice(0, 50);
        const trimmedMissDistances = missDistances.slice(0, 50);
        const trimmedCloseApproachData = closeApproachData.slice(0, 50);

        const trimmedEarthDates = hasEarthData ? earthDates.slice(0, 50) : [];
        const trimmedEarthMissDistances = hasEarthData ? earthMissDistances.slice(0, 50) : [];

        if (dates.length === 0 || missDistances.length === 0) {
            console.error("Dati non disponibili per il grafico.");
            return;
        }

        // Trova la distanza minima per evidenziare il cerchio rosso
        const trimmedMinDistance = Math.min(...trimmedMissDistances);
        const trimmedMinIndex = trimmedMissDistances.indexOf(trimmedMinDistance);


        // Prepara i dataset
        const datasets = [
            {
                label: "Tutti i corpi",
                data: trimmedMissDistances,
                borderColor: "rgb(0,130,255)",
                borderWidth: 2,
                fill: false,
                pointRadius: function(context) {
                    // Cerchio rosso più grande se è la distanza minima
                    return context.dataIndex === trimmedMinIndex ? 6 : 2;
                },
                pointBackgroundColor: function (context) {
                    // Cambia il colore del cerchio se la distanza è minima
                    return context.dataIndex === trimmedMinIndex ? "rgb(255, 0, 0)" : "rgb(21,100,181)";
                },
                tension: 0.5
            }
        ];

        // Aggiungi il dataset della Terra solo se ci sono dati
        if (hasEarthData) {
            datasets.push({
                label: "Solo Terra",
                data: createEarthDataPoints(trimmedDates, trimmedEarthDates, trimmedEarthMissDistances),
                borderColor: "rgb(255,0,0)",
                borderWidth: 2,
                fill: false,
                pointRadius: 3,
                pointBackgroundColor: "rgb(255,0,0)",
                tension: 0.5
            });
        }

        // Configurazione del grafico
        const chartConfig = {
            type: "line",
            data: {
                labels: trimmedDates,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: "top",
                    },
                    tooltip: {
                        enabled: false, // Disattiva il tooltip standard
                        external: customTooltip // Usa un tooltip personalizzato
                    }
                },
                interaction: {
                    mode: "nearest",
                    intersect: true
                },
                scales: {
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        },
                        title: {
                            display: true,
                            text: "Data di Avvicinamento"
                        }
                    },
                    y: {
                        beginAtZero: false,
                        suggestedMin: Math.min(...trimmedMissDistances) * 0.9,
                        suggestedMax: Math.max(...trimmedMissDistances) * 1.1,
                        title: {
                            display: true,
                            text: "Distanza di Avvicinamento (km)"
                        }
                    }
                },
                elements: {
                    line: { tension: 0.5 }
                }
            }
        };


        /*** Grafico Popup ***/
        // Trova la distanza minima per evidenziare il cerchio rosso
        const MinDistance = Math.min(...missDistances);
        const MinIndex = missDistances.indexOf(MinDistance);

        // Prepara i dataset per il popup
        const popupDatasets = [
            {
                label: "Tutti i corpi",
                data: missDistances,
                borderColor: "rgb(0,130,255)",
                borderWidth: 2,
                fill: false,
                pointRadius: function(context) {
                    // Cerchio rosso più grande se è la distanza minima
                    const MinDistance = Math.min(...missDistances);
                    const MinIndex = missDistances.indexOf(MinDistance);
                    return context.dataIndex === MinIndex ? 6 : 3;
                },
                pointBackgroundColor: function (context) {
                    // Cambia il colore del cerchio se la distanza è minima
                    const MinDistance = Math.min(...missDistances);
                    const MinIndex = missDistances.indexOf(MinDistance);
                    return context.dataIndex === MinIndex ? "rgb(255, 0, 0)" : "rgb(21,100,181)";
                },
                tension: 0.5
            }
        ];

        // Aggiungi il dataset della Terra al popup solo se ci sono dati
        if (hasEarthData) {
            popupDatasets.push({
                label: "Solo Terra",
                data: createEarthDataPoints(dates, earthDates, earthMissDistances),
                borderColor: "rgb(255,0,0)",  // Verde chiaro
                borderWidth: 2,
                fill: false,
                pointRadius: 3,
                pointBackgroundColor: "rgb(255,0,0)",
                tension: 0.5
            });
        }

        // Configurazione del grafico
        const PopupChartConfig = {
            type: "line",
            data: {
                labels: dates,
                datasets: popupDatasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: "top",
                    },
                    tooltip: {
                        enabled: false, // Disattiva il tooltip standard
                        external: customTooltip // Usa un tooltip personalizzato
                    }
                },
                interaction: {
                    mode: "nearest",
                    intersect: true
                },
                scales: {
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        },
                        title: {
                            display: true,
                            text: "Data di Avvicinamento"
                        }
                    },
                    y: {
                        beginAtZero: false,
                        suggestedMin: Math.min(...missDistances) * 0.9,
                        suggestedMax: Math.max(...missDistances) * 1.1,
                        title: {
                            display: true,
                            text: "Distanza di Avvicinamento (km)"
                        }
                    }
                },
                elements: {
                    line: { tension: 0.5 }
                }
            }
        };

        // Funzione per creare punti dati allineati alle date principali
        function createEarthDataPoints(allDates, earthDates, earthDistances) {
            // Crea un array di null della stessa lunghezza di tutte le date
            const result = new Array(allDates.length).fill(null);

            // Per ogni data di avvicinamento alla Terra
            earthDates.forEach((date, index) => {
                // Trova l'indice corrispondente nelle date generali
                const matchingIndex = allDates.indexOf(date);
                if (matchingIndex !== -1) {
                    // Se la data esiste, inserisci la distanza in quella posizione
                    result[matchingIndex] = earthDistances[index];
                }
            });

            return result;
        }
        let tooltipTimeout;

        function customTooltip(context) {
            let tooltipEl = document.getElementById("chart-tooltip");

            if (!tooltipEl) {
                tooltipEl = document.createElement("div");
                tooltipEl.id = "chart-tooltip";
                tooltipEl.style.position = "absolute";
                tooltipEl.style.background = "rgba(0, 0, 0, 0.8)";
                tooltipEl.style.color = "white";
                tooltipEl.style.padding = "8px";
                tooltipEl.style.borderRadius = "5px";
                tooltipEl.style.pointerEvents = "auto";
                tooltipEl.style.whiteSpace = "nowrap";
                tooltipEl.style.fontSize = "14px";
                tooltipEl.style.zIndex = "1000";
                tooltipEl.style.opacity = "0";
                tooltipEl.style.display = "none";
                document.body.appendChild(tooltipEl);

                tooltipEl.addEventListener("mouseenter", () => {
                    clearTimeout(tooltipTimeout);
                    fadeIn(tooltipEl);
                });
                tooltipEl.addEventListener("mouseleave", () => {
                    tooltipTimeout = setTimeout(() => {
                        fadeOut(tooltipEl);
                    }, 1000);
                });
            }

            const tooltip = context.tooltip;

            if (!tooltip || tooltip.opacity === 0) {
                tooltipTimeout = setTimeout(() => {
                    fadeOut(tooltipEl);
                }, 1000);
                return;
            }

            clearTimeout(tooltipTimeout);

            const index = tooltip.dataPoints[0].dataIndex;
            const data = closeApproachData[index];
            if (!data) return;

            tooltipEl.innerHTML = `
                <b>📅 Data:</b> ${data.close_approach_date} <br>
                <b>🚀 Velocità:</b> ${data.relative_velocity_km_s} km/s <br>
                <b>🌕 Distanza (LD):</b> ${data.miss_distance_ld || "N/A"} <br>
                <b>🌍 Distanza (AU):</b> ${data.miss_distance_au || "N/A"} <br>
                <b>🛰️ Distanza (km):</b> ${Number(data.miss_distance_km).toLocaleString()} <br>
                <b>🔄 Corpo orbitante:</b> ${data.orbiting_body} <br>
                    ${(data.orbiting_body === "Terra" || data.orbiting_body === "Luna") ? `
                    <u style="cursor:pointer;" onclick="showDistancePopup(${data.miss_distance_ld || 'null'})">
                        Visualizzazione 3D
                    </u>
                ` : ""}
            `;

            const canvas = context.chart.canvas;
            const canvasRect = canvas.getBoundingClientRect();

            let tooltipLeft = canvasRect.left + window.pageXOffset + tooltip.caretX + 10;
            const tooltipTop = canvasRect.top + window.pageYOffset + tooltip.caretY - tooltipEl.offsetHeight - 10;

            // Controllo: se tooltip esce a destra, lo spostiamo verso sinistra
            const tooltipWidth = tooltipEl.offsetWidth;
            const maxRight = tooltipLeft + tooltipWidth;
            const windowWidth = window.innerWidth;

            if (maxRight > windowWidth) {
                tooltipLeft = windowWidth - tooltipWidth - 20;
            }

            tooltipEl.style.left = `${tooltipLeft}px`;
            tooltipEl.style.top = `${tooltipTop}px`;

            fadeIn(tooltipEl);
        }

        // Funzione per far apparire il tooltip con una transizione di opacità
        function fadeIn(element) {
            let opacity = 0;
            element.style.display = "block";
            let interval = setInterval(() => {
                if (opacity >= 1) {
                    clearInterval(interval);
                }
                opacity += 0.05; // Incrementa l'opacità
                element.style.opacity = opacity;
            }, 30); // Ogni 15ms aumenta l'opacità
        }

        // Funzione per far scomparire il tooltip con una transizione di opacità
        function fadeOut(element) {
            let opacity = 1;
            let interval = setInterval(() => {
                if (opacity <= 0) {
                    clearInterval(interval);
                    element.style.display = "none"; // Nascondi il tooltip dopo che è completamente invisibile
                }
                opacity -= 0.05; // Diminuisce l'opacità
                element.style.opacity = opacity;
            }, 30); // Ogni 15ms diminuisce l'opacità
        }

        // Creazione dei grafici
        new Chart(document.getElementById("closeApproachChart").getContext("2d"), chartConfig);
        new Chart(document.getElementById("expandedCloseApproachChart").getContext("2d"), PopupChartConfig);
    });

    function openChartPopup() {
        document.getElementById("chartPopup").classList.remove("hidden");
    }

    function closeChartPopup() {
        document.getElementById("chartPopup").classList.add("hidden");
    }
</script>
