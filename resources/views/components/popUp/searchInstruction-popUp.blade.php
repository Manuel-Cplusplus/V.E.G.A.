<!-- Popup Modal -->
<div id="InstructionPopupModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center opacity-0 invisible transition-all duration-300">
    <div class="bg-white text-black p-6 rounded-2xl border-2 border-black text-center w-[70rem] max-w-4xl overflow-y-auto max-h-[80vh]">

        <!-- Icona chiusura -->
        <button id="closeInstructionPopupBtn" class=" relative top-2 left-96 text-gray-500 hover:text-gray-700">X</button>


        <!-- Icona informativa -->
        <div class="flex items-center justify-center mb-1">
            <svg class="w-10 h-10 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/>
            </svg>
        </div>

        <h2 class="text-xl font-bold mb-5">Istruzioni</h2>

        <div class="text-left text-sm leading-relaxed grid grid-cols-2 gap-6">
            <!-- Istruzioni Preliminari -->
            <div>
                <h3 class="font-semibold border-b pb-1 mb-2">Informazioni Preliminari</h3>
                <p>Questa sezione consente di ricercare informazioni dettagliate su un asteroide o una cometa che si avvicina a un corpo celeste, inserendo un ID, una denominazione o utilizzando i filtri.</p>
                <p>Se il risultato è un singolo record, verranno mostrati dettagli approfonditi con rappresentazioni grafiche. Se i risultati sono multipli, verrà visualizzato un elenco di asteroidi che soddisfano i criteri di ricerca.</p>
            </div>
            <!-- Modalità di Ricerca -->
            <div>
                <h3 class="font-semibold border-b pb-1 mb-2">Modalità di Ricerca</h3>
                <ul class="list-disc ml-4">
                    <li>Cerca solo con ID o nome: dettagli su quell'asteroide.</li>
                    <li>Cerca solo con i filtri: elenco di asteroidi che soddisfano i criteri.</li>
                    <li>Cerca con ID e filtri: dettagli sull'asteroide filtrati per criteri specifici (es. ID + data).</li>
                </ul>
                <p class="mt-2">Per informazioni tecniche dettagliate o per trovare facilmente la designation di un asteroide, visita: <a href="https://ssd.jpl.nasa.gov/tools/sbdb_lookup.html#/" class="text-blue-500 underline">SSD JPL Small-Body Database Lookup</a></p>
            </div>
        </div>

        <!-- Filtri -->
        <h3 class="font-semibold border-b pb-1 mb-2 mt-6">Filtri</h3>
        <p class="text-sm">Posizionando il cursore su ciascun filtro, comparirà una descrizione dettagliata.</p>
        <p class="text-sm"><strong>Nota:</strong> Solo gli utenti autenticati possono utilizzare i filtri avanzati, in quanto richiedono conoscenze più tecniche.</p>

        <!-- Classificazioni Orbitali -->
        <h3 class="font-semibold border-b pb-1 mb-2 mt-6">Classificazioni Orbitali (SBDB)</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse border border-gray-300">
                <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 px-2 py-1">Sigla</th>
                    <th class="border border-gray-300 px-2 py-1">Descrizione</th>
                </tr>
                </thead>
                <tbody>
                <tr><td class="border border-gray-300 px-2 py-1"><strong>IEO</strong></td><td class="border border-gray-300 px-2 py-1">Asteroide con orbita contenuta interamente entro l'orbita terrestre (Q < 0.983 UA).</td></tr>
                <tr><td class="border border-gray-300 px-2 py-1"><strong>ATE</strong></td><td class="border border-gray-300 px-2 py-1">Asteroide con orbita simile a 2062 Aten (a < 1.0 UA; Q > 0.983 UA).</td></tr>
                <tr><td class="border border-gray-300 px-2 py-1"><strong>APO</strong></td><td class="border border-gray-300 px-2 py-1">Asteroide con orbita simile a 1862 Apollo (a > 1.0 UA; q < 1.017 UA).</td></tr>
                <tr><td class="border border-gray-300 px-2 py-1"><strong>AMO</strong></td><td class="border border-gray-300 px-2 py-1">Asteroide con orbita simile a 1221 Amor (1.017 UA < q < 1.3 UA).</td></tr>
                <tr><td class="border border-gray-300 px-2 py-1"><strong>MCA</strong></td><td class="border border-gray-300 px-2 py-1">Asteroide che incrocia l'orbita di Marte (1.3 UA < q < 1.666 UA; a < 3.2 UA).</td></tr>
                <tr><td class="border border-gray-300 px-2 py-1"><strong>IMB</strong></td><td class="border border-gray-300 px-2 py-1">Asteroide della fascia interna (a < 2.0 UA; q > 1.666 UA).</td></tr>
                <tr><td class="border border-gray-300 px-2 py-1"><strong>MBA</strong></td><td class="border border-gray-300 px-2 py-1">Asteroide della fascia principale (2.0 UA < a < 3.2 UA; q > 1.666 UA).</td></tr>
                <tr><td class="border border-gray-300 px-2 py-1"><strong>OMB</strong></td><td class="border border-gray-300 px-2 py-1">Asteroide della fascia esterna (3.2 UA < a < 4.6 UA).</td></tr>
                <tr><td class="border border-gray-300 px-2 py-1"><strong>TJN</strong></td><td class="border border-gray-300 px-2 py-1">Troiano di Giove (4.6 UA < a < 5.5 UA; e < 0.3).</td></tr>
                <tr><td class="border border-gray-300 px-2 py-1"><strong>CEN</strong></td><td class="border border-gray-300 px-2 py-1">Centauro (5.5 UA < a < 30.1 UA).</td></tr>
                <tr><td class="border border-gray-300 px-2 py-1"><strong>TNO</strong></td><td class="border border-gray-300 px-2 py-1">Oggetto Transnettuniano (a > 30.1 UA).</td></tr>

                <tr><td class="border border-gray-300 px-2 py-1"><strong>PAA</strong></td><td class="border border-gray-300 px-2 py-1">Asteroide su orbita parabolica (e = 1.0).</td></tr>
                <tr><td class="border border-gray-300 px-2 py-1"><strong>HYA</strong></td><td class="border border-gray-300 px-2 py-1">Asteroide su orbita iperbolica (e > 1.0).</td></tr>
                <tr><td class="border border-gray-300 px-2 py-1"><strong>HYP</strong></td><td class="border border-gray-300 px-2 py-1">Cometa su orbita iperbolica (e > 1.0).</td></tr>
                <tr><td class="border border-gray-300 px-2 py-1"><strong>PAR</strong></td><td class="border border-gray-300 px-2 py-1">Cometa su orbita parabolica (e = 1.0).</td></tr>
                <tr><td class="border border-gray-300 px-2 py-1"><strong>COM</strong></td><td class="border border-gray-300 px-2 py-1">Cometa senza classificazione orbitale specifica.</td></tr>
                <tr><td class="border border-gray-300 px-2 py-1"><strong>JFC</strong></td><td class="border border-gray-300 px-2 py-1">Cometa della famiglia di Giove (P < 20 anni).</td></tr>
                <tr><td class="border border-gray-300 px-2 py-1"><strong>HTC</strong></td><td class="border border-gray-300 px-2 py-1">Cometa tipo Halley (20 anni < P < 200 anni).</td></tr>
                <tr><td class="border border-gray-300 px-2 py-1"><strong>ETc</strong></td><td class="border border-gray-300 px-2 py-1">Cometa tipo Encke (Tj > 3; a < aJ).</td></tr>
                <tr><td class="border border-gray-300 px-2 py-1"><strong>CTc</strong></td><td class="border border-gray-300 px-2 py-1">Cometa tipo Chiron (Tj > 3; a > aJ).</td></tr>
                <tr><td class="border border-gray-300 px-2 py-1"><strong>JFc</strong></td><td class="border border-gray-300 px-2 py-1">Cometa della famiglia di Giove (2 < Tj < 3).</td></tr>
                </tbody>
            </table>
        </div>


        <button onclick="closeInstructionModal()" class="mt-4 bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition-colors duration-200">Chiudi</button>
    </div>
</div>


<style>
    #InstructionPopupModal {
        z-index: 1000;
    }
</style>

<script>
    function closeInstructionModal() {
        const modal = document.getElementById('InstructionPopupModal');
        modal.classList.add('invisible');  // Nasconde il modal
        modal.classList.remove('opacity-100');  // Rimuove l'opacità visibile
        modal.classList.add('opacity-0');  // Aggiunge opacità 0 per renderlo invisibile
    }
</script>


