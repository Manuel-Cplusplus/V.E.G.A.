{{--
    V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
    Copyright (c) 2025 Manuel Carlucci

    Licensed under CC BY-NC-SA 4.0: http://creativecommons.org/licenses/by-nc-sa/4.0/
--}}

<!-- Confronto Energia di Impatto -->
<div id="ImpactEnergyComparison-container" class="-z-10 mr-96 transform -translate-x-72 bg-white opacity-75 px-6 py-4 rounded-2xl border-black border-2 shadow-black shadow-2xl w-72 text-center absolute top-36 h-48 max-h-48">
    <h2 class="font-medium text-[16px] mb-1">Energia Comparabile a</h2>
    <p id="energy-comparison-name" class="font-bold text-lg"></p>
    <div class="relative flex flex-col items-center mt-2">
        <img id="energy-comparison-image" class="w-20 h-20" src="/media/icons/comparisons/fungo-nucleare.png" alt="Esplosione Hiroshima">
        <div class="absolute right-10 flex flex-col items-center text-sm">
            <span class="text-lg">↑</span>
            <div class="h-10 border-l-2 border-black"></div>
            <span class="text-lg">↓</span>
            <span class="absolute left-3 top-7" id="energy-comparison-size"></span>
        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        var impactEnergy = {{ $sentrySummary['energy'] * 1000 }}; // Energia in kilotoni (kT)
        updateEnergyComparison(impactEnergy);
    });

    function updateEnergyComparison(energyKilotons) {
        const hiroshimaEnergy = 15; // 15 kT = Hiroshima
        const ratio = energyKilotons / hiroshimaEnergy;

        const label = `${ratio.toFixed(1)} Hiroshima`;

        document.getElementById("energy-comparison-name").innerText = label;
        document.getElementById("energy-comparison-size").innerText = `${hiroshimaEnergy} kT`;

    }
</script>

