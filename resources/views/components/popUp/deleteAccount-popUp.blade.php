<!-- Popup di conferma eliminazione account -->
<div id="deleteAccountPopup" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96 text-center">

        <!-- Icona di allerta -->
        <div class="flex justify-center mb-2">
            <svg class="w-16 h-16 text-red-600" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <circle cx="12" cy="12" r="10" stroke="red" stroke-width="2" fill="none"></circle>
                <line x1="12" y1="8" x2="12" y2="14" stroke="red" stroke-width="2"></line>
                <circle cx="12" cy="17" r="0.1" fill="red"></circle>
            </svg>
        </div>

        <!-- Testo di avviso -->
        <h2 class="text-lg font-semibold mt-2 text-gray-900">Sei sicuro di voler eliminare il tuo account?</h2>
        <p class="text-gray-600 mt-2">Questa azione è irreversibile. Perderai tutti i tuoi dati.</p>

        <!-- Bottoni -->
        <div class="mt-4 flex gap-2">
            <button id="cancelDelete" class="w-1/2 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg font-semibold">Annulla</button>
            <form id="deleteAccountForm" method="post" action="{{ route('profile.destroy') }}" class="w-1/2">
                @csrf
                @method('delete')
                <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg font-semibold">Elimina</button>
            </form>
        </div>
    </div>
</div>

<style>
    #deleteAccountPopup {
        z-index: 1000;
    }
</style>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const deleteButton = document.querySelector('#deleteAccountButton');
        const popup = document.querySelector('#deleteAccountPopup');
        const cancelButton = document.querySelector('#cancelDelete');

        deleteButton.addEventListener('click', () => {
            popup.classList.remove('hidden');
        });

        cancelButton.addEventListener('click', () => {
            popup.classList.add('hidden');
        });
    });
</script>
