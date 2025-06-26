{{--
    V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
    Copyright (c) 2025 Manuel Carlucci

    Licensed under CC BY-NC-SA 4.0: http://creativecommons.org/licenses/by-nc-sa/4.0/
--}}

<div id="ImpactChartContent" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white p-6 rounded-xl w-5/6 h-5/6 relative flex flex-col items-center">

        <h3 class="text-xl font-bold mb-4">Grafico relativo al Numero di Impatti nel tempo</h3>

        <!-- Contenitore del Grafico -->
        <div class="w-full h-full flex items-center justify-center">
            <canvas id="impactChart"></canvas>
        </div>

        <!-- Bottone Chiudi -->
        <button onclick="closeImpactChart()" class=" bottom-2 right-1/2 translate-x-1/2 bg-red-500 text-white p-2 rounded-full">
            Chiudi
        </button>
    </div>
</div>

<!-- Dati per visualizzazione LLM in un div nascosto -->
<div id="chart-data" style="display:none;">
    <pre id="llm-json">
        {!! json_encode([
            'energyByYear' => $energyByYear,
            'impactCounts' => collect($energyByYear)->map(function ($item) {
                return $item['count']; // Calcola il conteggio per ogni anno
            })->all()
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
    </pre>
</div>


<script>
    function openImpactChart() {
        document.getElementById("ImpactChartContent").classList.remove("hidden");
    }

    function closeImpactChart() {
        document.getElementById("ImpactChartContent").classList.add("hidden");
    }
</script>

<script>
    // Passa i dati dal controller al JavaScript
    let energyByYearData = @json($energyByYear);

    // Estrai gli anni, il conteggio degli impatti
    const yearsOfImpact = Object.keys(energyByYearData);
    const impactCounts = yearsOfImpact.map(year => energyByYearData[year].count);

    // Configurazione del grafico
    const ctx2 = document.getElementById('impactChart').getContext('2d');
    const impactChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: yearsOfImpact, // Etichette sugli assi X (Anni)
            datasets: [{
                label: 'Numero di Impatti',
                data: impactCounts, // Dati sugli impatti per ogni anno
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Numero di Impatti'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Anno'
                    }
                }
            }
        }
    });
</script>





