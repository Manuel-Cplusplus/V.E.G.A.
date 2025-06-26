<!-- Popup con i filtri -->
<div id="filter-popup" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden border-black border-2">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-full border-2 border-black">
        <h3 class="text-xl font-semibold mb-4 text-black text-center">Filtri Applicati</h3>
        <ul class="text-gray-800">
            <li><strong>Data di filtro:</strong> {{ $filters['date-min'] ?? 'Non Specificata' }} - {{ $filters['date-max'] ?? 'Non Specificata' }}</li>
            <li><strong>Distanza: </strong> {{ $filters['dist-min'] ?? 'Non Specificata' }} {{ $filters['unit_min'] ?? '' }} - {{ $filters['dist-max'] ?? 'Non Specificata' }} {{ $filters['unit_max'] ?? '' }}</li>
            <li><strong>Magnitudine: </strong> {{ $filters['h-min'] ?? 'Non Specificata' }} - {{ $filters['h-max'] ?? 'Non Specificata' }}</li>
            <li><strong>Velocità: </strong> {{ $filters['v-rel-min'] ?? 'Non Specificata' }} km/s - {{ $filters['v-rel-max'] ?? 'Non Specificata' }} km/s</li>
            <li><strong>Classe:</strong> {{ $filters['class'] ?? 'Non Specificata' }}</li>
            <li><strong>Corpo Celeste:</strong> {{ $filters['body'] ?? 'Non Specificato' }}</li><br>
            <li> {{ isset($filters['pha']) && $filters['pha'] == true ? 'Selezionati solo i gli Asteroidi Pericolosi' : 'Visualizzati tutti gli asteroidi, compresi i potenzialmente pericolosi'}}</li> <!-- Gestisce il filtro PHA -->
            <li> Limite di 50 risultati</li>
        </ul>
        <div class="text-center mt-6">
            <button id="close-popup" class="bg-blue-500 text-white py-2 px-6 rounded-lg hover:bg-blue-600">Chiudi</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterIcon = document.getElementById('filter-icon');
        const filterPopup = document.getElementById('filter-popup');
        const closePopup = document.getElementById('close-popup');

        // Mostra il popup quando clicchi sull'icona dei filtri
        filterIcon.addEventListener('click', function() {
            filterPopup.classList.remove('hidden');
        });

        // Chiudi il popup quando clicchi su "Chiudi"
        closePopup.addEventListener('click', function() {
            filterPopup.classList.add('hidden');
        });
    });
</script>
