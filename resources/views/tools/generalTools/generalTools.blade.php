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

<main class="flex flex-col items-center">

    <div class="w-2/3 items-center">
        <h2 class="text-2xl font-bold text-green-400 flex items-center mt-5">
            <span class="flex-grow h-0.5 bg-white mr-2"></span>
            &#10022; Strumenti Generali &#10022;
            <span class="flex-grow h-0.5 bg-white ml-2"></span>
        </h2>
    </div>


    <!-- Container strumenti affiancati -->
    <div class="flex flex-row col-span-3 gap-6 w-full px-10 justify-center mt-5">

        <!-- Convertitore Distanze -->
        <section
                class="w-1/3 bg-white rounded-lg p-4 opacity-80 shadow-[8px_0_15px_rgba(0,0,0,0.8),-8px_0_15px_rgba(0,0,0,0.8)]">
            <h2 class="text-xl font-bold mb-4 text-center"> Convertitore Distanze</h2>

            <div class="text-sm text-gray-600 bg-gray-100 rounded-md p-3 mb-4">
                <p><strong>1 LD</strong> (Lunar Distance) = <strong>384,400 km</strong></p>
                <p><strong>1 AU</strong> (Astronomical Unit) = <strong>149,597,870.7 km</strong></p>
            </div>

            <div class="flex flex-row col-span-3 gap-4">
                <div>
                    <label for="inputValue" class="block text-sm font-medium mb-1">Valore</label>
                    <input id="inputValue" type="number" step="any" placeholder="Es. 384400"
                           class="w-48 border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-200"/>
                </div>
                <div>
                    <label for="inputUnit" class="block text-sm font-medium mb-1">Da</label>
                    <select id="inputUnit"
                            class="w-20 border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-200">
                        <option value="km">km</option>
                        <option value="ld">LD</option>
                        <option value="au">AU</option>
                    </select>
                </div>
                <div>
                    <label for="outputUnit" class="block text-sm font-medium mb-1">A</label>
                    <select id="outputUnit"
                            class="w-20 border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-200">
                        <option value="km">km</option>
                        <option value="ld">LD</option>
                        <option value="au">AU</option>
                    </select>
                </div>
            </div>
            <div class="mt-4 text-center">
                <button onclick="convertDistance()"
                        class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    Converti
                </button>
            </div>
            <div id="resultBox" class="mt-2 text-center text-[14px] font-semibold text-green-600 hidden"></div>
        </section>


        <!-- Convertitore Energia -->
        <section
                class="w-1/3 bg-white rounded-lg p-4 opacity-80 shadow-[8px_0_15px_rgba(0,0,0,0.8),-8px_0_15px_rgba(0,0,0,0.8)]">
            <h2 class="text-xl font-bold mb-4 text-center"> Convertitore Energia</h2>

            <div class="text-sm text-gray-600 bg-gray-100 rounded-md p-3 mb-4">
                <p><strong>1 kilojoule (kJ)</strong> = 1,000 joule (J)</p>
                <p><strong>1 kiloton TNT</strong> ≈ 4.184 × 10¹² J</p>
                <p><strong>1 megaton TNT</strong> ≈ 4.184 × 10¹⁵ J</p>
            </div>

            <div class="flex flex-row col-span-2 gap-4">
                <div>
                    <label for="energyInput" class="block text-sm font-medium mb-1">Valore energia</label>
                    <input id="energyInput" type="number" step="any" placeholder="Es. 1000000"
                           class="w-48 border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-200"/>
                </div>
                <div>
                    <label for="energyUnit" class="block text-sm font-medium mb-1">Unità di partenza</label>
                    <select id="energyUnit"
                            class="w-32 border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-200">
                        <option value="joule">Joule (J)</option>
                        <option value="kilojoule">Kilojoule (kJ)</option>
                        <option value="kiloton">Kiloton TNT</option>
                        <option value="megaton">Megaton TNT</option>
                    </select>
                </div>
            </div>

            <div id="energyResults"
                 class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4 text-center text-[14px] font-semibold"></div>
        </section>


        <!-- Selezione Distanza in LD -->
        <section
                class="w-1/3 bg-white rounded-lg p-4 opacity-80 shadow-[8px_0_15px_rgba(0,0,0,0.8),-8px_0_15px_rgba(0,0,0,0.8)]">
            <h2 class="text-xl font-bold mb-4 text-center"> Visualizza distanza in LD</h2>

            <div class="flex flex-col space-y-4 items-center">
                <label for="ldInput" class="text-sm font-medium">Inserisci distanza (min. 0.1 LD)</label>

                <input type="number" id="ldInput" value="1" min="0.1" step="0.1"
                       class="w-40 text-center border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-indigo-200"/>

                <input type="range" id="ldSlider" value="1" min="0.1" max="50" step="0.1"
                       class="w-full max-w-xs accent-indigo-600"/>

                <button onclick="launchLdPopup()"
                        class="mt-4 px-6 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                    Visualizza in 3D
                </button>
            </div>
        </section>
    </div>


    <!-- Visualizzazione 3D -->
    @include('tools.generalTools.distanceVisualization')

    <!-- Popup Modal -->
    @include('components.popUp.login-popUp')
</main>

</body>

{{-- @include('layouts.footer') --}}
</html>

<script>
    /** Convertitore Distanza **/
    const LD_IN_KM = 384400;
    const AU_IN_KM = 149597870.7;

    function convertDistance() {
        const inputValue = parseFloat(document.getElementById('inputValue').value);
        const inputUnit = document.getElementById('inputUnit').value;
        const outputUnit = document.getElementById('outputUnit').value;
        const resultBox = document.getElementById('resultBox');

        if (isNaN(inputValue)) {
            resultBox.classList.remove('hidden');
            resultBox.innerText = "Inserisci un valore valido.";
            return;
        }

        if (inputValue < 0) {
            alert("Il valore non può essere negativo.");
            resultBox.classList.add('hidden');
            return;
        }

        let km;
        switch (inputUnit) {
            case 'km':
                km = inputValue;
                break;
            case 'ld':
                km = inputValue * LD_IN_KM;
                break;
            case 'au':
                km = inputValue * AU_IN_KM;
                break;
        }

        let output;
        switch (outputUnit) {
            case 'km':
                output = km;
                break;
            case 'ld':
                output = km / LD_IN_KM;
                break;
            case 'au':
                output = km / AU_IN_KM;
                break;
        }

        resultBox.classList.remove('hidden');
        resultBox.innerText = `${inputValue} ${inputUnit} = ${output.toFixed(6)} ${outputUnit}`;
    }
</script>


<script>
    /** Visualizzatore 3D **/
    function launchLdPopup() {
        const ld = parseFloat(document.getElementById('ldInput').value);
        if (ld >= 0.1) {
            showDistancePopup(ld);
        } else {
            alert("Inserisci un valore di almeno 0.1 LD.");
        }
    }

    // Sincronizzazione input numerico e slider
    const ldInput = document.getElementById('ldInput');
    const ldSlider = document.getElementById('ldSlider');

    ldInput.addEventListener('input', () => {
        ldSlider.value = ldInput.value;
    });

    ldSlider.addEventListener('input', () => {
        ldInput.value = ldSlider.value;
    });
</script>


<script>
    /** Convertitore Energia **/
    const JOULE_PER_KJ = 1000;
    const JOULE_PER_KT = 4.184e12;
    const JOULE_PER_MT = 4.184e15;

    const energyInput = document.getElementById('energyInput');
    const energyUnit = document.getElementById('energyUnit');

    const outputJ = document.getElementById('outputJ');
    const outputKT = document.getElementById('outputKT');
    const outputMT = document.getElementById('outputMT');

    energyInput.addEventListener('input', convertEnergy);
    energyUnit.addEventListener('change', convertEnergy);

    function convertEnergy() {
        const value = parseFloat(energyInput.value);
        const unit = energyUnit.value;
        const resultsContainer = document.getElementById('energyResults');

        if (isNaN(value)) {
            resultsContainer.innerHTML = '';
            return;
        }

        if (value < 0) {
            alert('Inserisci un valore positivo per la conversione dell’energia.');
            energyInput.value = '';
            resultsContainer.innerHTML = '';
            return;
        }

        let joule;
        switch (unit) {
            case 'joule':
                joule = value;
                break;
            case 'kilojoule':
                joule = value * JOULE_PER_KJ;
                break;
            case 'kiloton':
                joule = value * JOULE_PER_KT;
                break;
            case 'megaton':
                joule = value * JOULE_PER_MT;
                break;
        }

        const results = [];

        if (unit !== 'joule') {
            results.push(`<div><span class="text-gray-500">Joule:</span> ${joule.toExponential(3)} J</div>`);
        }
        if (unit !== 'kiloton') {
            results.push(`<div><span class="text-gray-500">Kiloton TNT:</span> ${(joule / JOULE_PER_KT).toExponential(3)} kt TNT</div>`);
        }
        if (unit !== 'megaton') {
            results.push(`<div><span class="text-gray-500">Megaton TNT:</span> ${(joule / JOULE_PER_MT).toExponential(3)} Mt TNT</div>`);
        }
        if (unit !== 'kilojoule') {
            results.push(`<div><span class="text-gray-500">Kilojoule:</span> ${(joule / JOULE_PER_KJ).toExponential(3)} kJ</div>`);
        }

        resultsContainer.innerHTML = results.join('');
    }

</script>
