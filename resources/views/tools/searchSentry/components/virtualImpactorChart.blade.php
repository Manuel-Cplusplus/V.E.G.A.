{{--
    V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
    Copyright (c) 2025 Manuel Carlucci

    Licensed under CC BY-NC-SA 4.0: http://creativecommons.org/licenses/by-nc-sa/4.0/
--}}

<!-- Grafico Incontri Ravvicinati -->
<div class="bg-white opacity-75 px-6 py-4 rounded-2xl border-black border-2 shadow-black shadow-2xl max-w-md">
    <h2 class="font-extrabold text-[18px] underline text-center mb-2">Possibili Impatti Futuri</h2>
    <!-- Icona per ingrandire -->
    <button onclick="openChartPopup()" class="absolute p-2 bg-blue-500 text-white rounded-full transform translate-x-80 -translate-y-9">
        <!-- Icona di ingrandimento -->
        <i class="fas fa-search-plus"></i>
    </button>
    <div class="w-full h-[210px]">
        <canvas id="VirtualImpactorChart"></canvas>
    </div>
    <p class="mt-2 text-xs text-gray-600 text-center italic">
        Per maggiori dettagli su questi risultati, visitare la sezione <strong>"Teoria"</strong> del sistema.
    </p>
</div>

<!-- Pop-up per ingrandire il grafico -->
<div id="chartPopup" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white p-6 rounded-xl w-5/6 h-5/6 relative flex flex-col items-center">
        <h2 class="font-extrabold text-[18px] underline text-center mb-2">Possibili Impatti Futuri</h2>

        <!-- Contenitore del Grafico -->
        <div class="w-full h-full flex items-center justify-center">
            <canvas id="expandedVirtualImpactorChart"></canvas>
        </div>
        <p class="mt-2 mb-4 text-xs text-gray-600 text-center italic">
            Per maggiori dettagli su questi risultati, visitare la sezione <strong>"Teoria"</strong> del sistema.
        </p>


        <!-- Bottone Chiudi -->
        <button onclick="closeChartPopup()" class="bottom-2 right-1/2 translate-x-1/2 bg-red-500 text-white p-2 rounded-full">
            Chiudi
        </button>
    </div>
</div>


<!-- Dati per visualizzazione LLM in un div nascosto -->
<div id="chart-data" style="display:none;">
    <pre id="llm-json">
        {!! json_encode([
            'chartData' => $chartData,
            'chartDataExpanded' => $chartDataExpanded
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
    </pre>
</div>


<script>
    function openChartPopup() {
        document.getElementById("chartPopup").classList.remove("hidden");
    }

    function closeChartPopup() {
        document.getElementById("chartPopup").classList.add("hidden");
    }
</script>



<script>
    /** Grafico Principale **/
    const lineChartData = @json($chartData);

    const ctx = document.getElementById('VirtualImpactorChart').getContext('2d');

    // Ordina i dati per data - altrimenti il grafico non esce correttamente
    lineChartData.sort((a, b) => new Date(a.x) - new Date(b.x));

    const dataPoints = lineChartData.map(item => ({
        x: item.x,
        y: item.y,
        tooltipDate: item.tooltipDate,
        full: item.full
    }));

    const chart = new Chart(ctx, {
        type: 'line',  // Cambiato da 'bubble' a 'line'
        data: {
            datasets: [{
                label: 'Possibili Impatti',
                data: dataPoints,
                backgroundColor: 'rgba(13,230,29,0.68)',
                borderColor: 'rgb(12,163,23)',
                borderWidth: 1,
                fill: false,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const d = context.raw?.full;
                            if (!d) return 'Dati non disponibili';

                            const lines = [];
                            lines.push(`📅 Data: ${context.raw.tooltipDate}`);
                            if (d.ip) lines.push(`☄️ Prob. Impatto: ${d.ip}`);
                            if (d.energy) lines.push(`💥 Energia: ${d.energy} Mt`);
                            if (d.ps) lines.push(`📈 Palermo Scale (PS): ${d.ps}`);
                            if (d.ts) lines.push(`🧭 Torino Scale (TS): ${d.ts}`);

                            if (d.sigma_vi) lines.push(`🔍 Sigma VI (IOBS): ${d.sigma_vi}`);
                            if (d.dist) lines.push(`📍 Distanza (LOV): ${d.dist} rE`);
                            if (d.width) lines.push(`🌀 Larghezza (LOV): ${d.width} rE`);
                            if (d.sigma_imp) lines.push(`📏 Sigma IMP (LOV): ${d.sigma_imp}`);
                            if (d.sigma_lov) lines.push(`📐 Sigma LOV: ${d.sigma_lov}`);
                            if (d.stretch) lines.push(`↔️ Stretch: ${d.stretch} rE/σ`);

                            if (d.sigma_mc) lines.push(`🧮 Sigma MC: ${d.sigma_mc}`);

                            return lines; // Chart.js mostrerà ogni stringa su una riga
                        }
                    }
                },
                title: {
                    display: false,
                    text: 'Grafico a Linee: Impatti Futuri'
                }
            },
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'year'
                    },
                    title: {
                        display: true,
                        text: 'Data'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Probabilità di Impatto'
                    },
                    ticks: {
                        maxTicksLimit: 6
                    }
                }
            }
        }
    });

</script>


<script>
    /** Grafico Esteso **/
    const expandedLineChartData = @json($chartDataExpanded);
    document.addEventListener('DOMContentLoaded', function () {
        const expandedCtx = document.getElementById('expandedVirtualImpactorChart').getContext('2d');

        // Ordina i dati per data - altrimenti il grafico non esce correttamente
        expandedLineChartData.sort((a, b) => new Date(a.x) - new Date(b.x));

        const expandedDataPoints = expandedLineChartData.map(item => ({
            x: item.x,
            y: item.y,
            tooltipDate: item.tooltipDate,
            full: item.full
        }));

        const expandedChart = new Chart(expandedCtx, {
            type: 'line',  // Cambiato da 'bubble' a 'line'
            data: {
                datasets: [{
                    label: 'Tutti gli Impatti Futuri',
                    data: expandedDataPoints,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 3,
                    fill: false,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const d = context.raw.full;
                                const time = context.raw.tooltipDate;
                                return [
                                    `Data: ${time}`,
                                    `Prob. Impatto: ${d.ip}`,
                                    `PS: ${d.ps}`,
                                    `TS: ${d.ts}`,
                                    `Sigma VI: ${d.sigma_vi}`
                                ];
                            }
                        }
                    },
                    title: {
                        display: false,
                        text: 'Grafico Esteso: Tutti gli Impatti Futuri'
                    }
                },
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'year'
                        },
                        title: {
                            display: true,
                            text: 'Data'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Probabilità di Impatto'
                        },
                        ticks: {
                            maxTicksLimit: 6
                        }
                    }
                }
            }
        });
    });

</script>
