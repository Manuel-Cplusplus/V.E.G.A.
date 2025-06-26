{{--
    V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
    Copyright (c) 2025 Manuel Carlucci

    Licensed under CC BY-NC-SA 4.0: http://creativecommons.org/licenses/by-nc-sa/4.0/
--}}

<!-- Tabella per gli asteroidi selezionati -->
<div id="asteroidsTable" class="mt-4">
    <table class="w-full border-collapse mt-4" id="comparisonTable">
        <thead>
        <tr class="bg-gray-200">
            <th class="border border-gray-300 px-2 py-1 text-sm">Nome</th>
            <th class="border border-gray-300 px-2 py-1 text-sm">Diametro (m)</th>
            <th class="border border-gray-300 px-2 py-1 text-sm">Distanza (km)</th>
            <th class="border border-gray-300 px-2 py-1 text-sm">Velocità (km/s)</th>
            <th class="border border-gray-300 px-2 py-1 text-sm">Pericoloso</th>
            <th class="border border-gray-300 px-2 py-1 text-sm">Rimuovi</th>
        </tr>
        </thead>
        <tbody id="comparisonTableBody">
        <!-- Qui verranno inseriti gli asteroidi col js -->
        </tbody>
    </table>
</div>

<!-- Messaggio quando non ci sono asteroidi -->
<p id="noAsteroidsMessage" class="italic text-lg mt-20">Ancora Nessun Asteroide Selezionato</p>

<!-- Pulsante per confrontare -->
<button id="compareButton" class="mt-6 px-4 py-2 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 transition-all hidden">
    Confronta Asteroidi
</button>



<!-- Script per la aggiunta e rimozione di asteroidi -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Array per memorizzare gli asteroidi selezionati
        let selectedAsteroids = [];

        // Array per memorizzare i dati dettagliati degli asteroidi (inclusi i dati passati dal controller)
        let detailedAsteroidData = {};

        const MAX_ASTEROIDS = 5;

        // Recupera elementi UI
        const addToComparisonBtn = document.querySelector('img[alt="add_to_comparison"]');
        const removeFromComparisonBtn = document.querySelector('img[alt="remove_to_comparison"]');
        const comparisonTable = document.getElementById('comparisonTable');
        const comparisonTableBody = document.getElementById('comparisonTableBody');
        const noAsteroidsMessage = document.getElementById('noAsteroidsMessage');
        const compareButton = document.getElementById('compareButton');

        // Funzione per aggiornare la visibilità
        function updateTableVisibility() {
            if (selectedAsteroids.length > 0) {
                comparisonTable.classList.remove('hidden');
                noAsteroidsMessage.classList.add('hidden');

                // Mostra il pulsante di confronto solo se ci sono almeno 2 asteroidi
                if (selectedAsteroids.length >= 2) {
                    compareButton.classList.remove('hidden');
                } else {
                    compareButton.classList.add('hidden');
                }
            } else {
                comparisonTable.classList.add('hidden');
                noAsteroidsMessage.classList.remove('hidden');
                compareButton.classList.add('hidden');
            }
        }

        // Inizializza lo stato
        updateTableVisibility();

        // Funzione per aggiungere un asteroide alla tabella di confronto
        function addAsteroidToComparison() {
            // Controlla se abbiamo già raggiunto il limite massimo
            if (selectedAsteroids.length >= MAX_ASTEROIDS) {
                alert('Puoi selezionare al massimo ' + MAX_ASTEROIDS + ' asteroidi per il confronto.');
                return;
            }

            // Recupera i dati dell'asteroide attualmente selezionato
            @if(isset($asteroidData))
            const asteroid = {
                id: "{{ $asteroidData['id'] ?? '' }}",
                name: "{{ $asteroidData['name'] ?? '' }}",
                diameter: "{{ !empty($asteroidData['diameter']) ? number_format($asteroidData['diameter'], 2) : 'N/D' }}",
                distance: "{{ !empty($asteroidData['miss_distance_km']) ? $asteroidData['miss_distance_km'] : 'N/D' }}",
                velocity: "{{ !empty($asteroidData['velocity_km_s']) ? $asteroidData['velocity_km_s'] : 'N/D' }}",
                isHazardous: "{{ isset($asteroidData['is_hazardous']) ? ($asteroidData['is_hazardous'] ? 'Sì' : 'No') : 'N/D' }}",
                diameter_uncertainty: "{{ !empty($asteroidData['diameter_uncertainty']) ? $asteroidData['diameter_uncertainty'] : 'N/D' }}",
                mass: "{{ isset($sentrySummary) && isset($sentrySummary['mass']) ? number_format($sentrySummary['mass'], 2) : 'N/D' }}",
                isSentryObject: "{{ isset($asteroidData['is_sentry_object']) ? ($asteroidData['is_sentry_object'] ? 'Sì' : 'No') : 'N/D' }}",
                impactProbability: "{{ isset($sentrySummary) && isset($sentrySummary['ip']) ? ('1 su ' . number_format(1 / $sentrySummary['ip'], 0, ',', '.')) : 'N/D' }}",
                impactEnergy: "{{ isset($sentrySummary) && isset($sentrySummary['energy']) ? ($sentrySummary['energy'] * 1000 . ' kT') : 'N/D' }}"
            };

            // Verifica se l'asteroide è già nella lista
            const exists = selectedAsteroids.some(a => a.id === asteroid.id);
            if (exists) {
                alert('Questo asteroide è già stato aggiunto al confronto.');
                return;
            }

            // Aggiungi l'asteroide all'array
            selectedAsteroids.push(asteroid);

            // Salva anche i dati dettagliati di questo asteroide
            saveDetailedAsteroidData();

            // Aggiorna la tabella
            renderComparisonTable();

            // Salva gli asteroidi nel localStorage
            saveAsteroidsToLocalStorage();
            @else
            alert('Nessun asteroide selezionato da aggiungere al confronto.');
            @endif
        }

        // Funzione per salvare tutti i dati dettagliati passati dal controller Laravel
        function saveDetailedAsteroidData() {
            @if(isset($asteroidData) && isset($asteroidData['id']))
            const asteroidId = "{{ $asteroidData['id'] }}";

            // Crea un oggetto per memorizzare tutti i dati
            detailedAsteroidData[asteroidId] = {
                asteroidData: @json($asteroidData ?? []),
                sentrySummary: @json($sentrySummary ?? []),
                closeApproachData: @json($closeApproachData ?? []),
                dates: @json($dates ?? []),
                missDistances: @json($missDistances ?? []),
                earthDates: @json($earthDates ?? []),
                earthMissDistances: @json($earthMissDistances ?? [])
            };

            // Salva questi dati in localStorage
            localStorage.setItem('detailedAsteroidData', JSON.stringify(detailedAsteroidData));
            @endif
        }

        // Funzione per caricare i dati dettagliati dal localStorage
        function loadDetailedAsteroidData() {
            const storedData = localStorage.getItem('detailedAsteroidData');
            if (storedData) {
                detailedAsteroidData = JSON.parse(storedData);
            }
        }

        // Funzione per rimuovere un asteroide dalla tabella di confronto
        function removeAsteroidFromComparison(asteroidId) {
            // Filtra l'array per rimuovere l'asteroide
            selectedAsteroids = selectedAsteroids.filter(a => a.id !== asteroidId);

            // Rimuovi anche i dati dettagliati
            if (detailedAsteroidData[asteroidId]) {
                delete detailedAsteroidData[asteroidId];
                localStorage.setItem('detailedAsteroidData', JSON.stringify(detailedAsteroidData));
            }

            // Aggiorna la tabella
            renderComparisonTable();

            // Salva gli asteroidi nel localStorage
            saveAsteroidsToLocalStorage();
        }

        // Funzione per rimuovere l'asteroide attualmente selezionato dal confronto
        function removeCurrentAsteroidFromComparison() {
            @if(isset($asteroidData))
            const currentId = "{{ $asteroidData['id'] ?? '' }}";

            // Controlla se l'asteroide è nella lista
            const exists = selectedAsteroids.some(a => a.id === currentId);
            if (!exists) {
                alert('Questo asteroide non è presente nella lista di confronto.');
                return;
            }

            // Rimuovi l'asteroide
            removeAsteroidFromComparison(currentId);
            @else
            alert('Nessun asteroide selezionato da rimuovere dal confronto.');
            @endif
        }

        // Funzione per renderizzare la tabella di confronto
        function renderComparisonTable() {
            // Svuota il corpo della tabella
            comparisonTableBody.innerHTML = '';

            // Aggiungi una riga per ogni asteroide
            selectedAsteroids.forEach(asteroid => {
                const row = document.createElement('tr');

                // Colonne della tabella
                row.innerHTML = `
                <td class="border border-gray-300 px-2 py-1 text-sm">${asteroid.name}</td>
                <td class="border border-gray-300 px-2 py-1 text-sm">${asteroid.diameter}</td>
                <td class="border border-gray-300 px-2 py-1 text-sm">${asteroid.distance}</td>
                <td class="border border-gray-300 px-2 py-1 text-sm">${asteroid.velocity}</td>
                <td class="border border-gray-300 px-2 py-1 text-sm ${asteroid.isHazardous === 'Sì' ? 'text-red-500 font-bold' : 'text-green-500 font-bold'}">${asteroid.isHazardous}</td>
                <td class="border border-gray-300 px-2 py-1 text-sm">
                    <button class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 text-xs"
                            onclick="window.removeAsteroidFromComparison('${asteroid.id}')">
                        ✕
                    </button>
                </td>
            `;

                comparisonTableBody.appendChild(row);
            });

            // Aggiorna la visibilità
            updateTableVisibility();
        }

        // Funzione per salvare gli asteroidi nel localStorage
        function saveAsteroidsToLocalStorage() {
            localStorage.setItem('selectedAsteroids', JSON.stringify(selectedAsteroids));
        }

        // Funzione per caricare gli asteroidi dal localStorage
        function loadAsteroidsFromLocalStorage() {
            const storedAsteroids = localStorage.getItem('selectedAsteroids');
            if (storedAsteroids) {
                selectedAsteroids = JSON.parse(storedAsteroids);
                renderComparisonTable();
            }
        }

        // Aggiungi event listener al pulsante "Aggiungi al Confronto"
        if (addToComparisonBtn) {
            addToComparisonBtn.addEventListener('click', addAsteroidToComparison);
        }

        // Aggiungi event listener al pulsante "Rimuovi dal Confronto"
        if (removeFromComparisonBtn) {
            removeFromComparisonBtn.addEventListener('click', removeCurrentAsteroidFromComparison);
        }

        // Esponi la funzione removeAsteroidFromComparison a livello globale
        window.removeAsteroidFromComparison = removeAsteroidFromComparison;

        // Gestisci il pulsante di confronto
        compareButton.addEventListener('click', function() {

            window.location.href = "{{ route('compareAsteroids.results') }}";
            //alert('Funzionalità di confronto dettagliato in fase di implementazione');
        });

        // Carica i dati dal localStorage all'avvio
        loadAsteroidsFromLocalStorage();
        loadDetailedAsteroidData();
    });
</script>

