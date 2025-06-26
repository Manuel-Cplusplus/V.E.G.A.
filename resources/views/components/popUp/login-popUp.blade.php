
<!-- Popup Modal -->
<div id="popupModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center opacity-0 invisible transition-all duration-300">
    <div class="bg-white text-black p-6 rounded-md border-2 border-black text-center w-96">
        <!-- Icona informativa -->
        <div class="flex items-center justify-center space-x-2 mb-4">
            <svg class="w-14 h-14 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/>
            </svg>
        </div>
        <p class="text-lg">Devi essere autenticato per usufruire della funzione dei Preferiti.</p>
        <button id="closePopupBtn" class="mt-4 bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition-colors duration-200">Chiudi</button>
    </div>
</div>

<style>
    #popupModal {
        z-index: 1000;
    }
</style>

<script>
    /* Gestione Popup Login */
    document.addEventListener("DOMContentLoaded", function() {
        // Seleziona gli elementi del popup
        const popupModal = document.getElementById("popupModal");
        const closePopupBtn = document.getElementById("closePopupBtn");

        // Funzioni per mostrare/nascondere il popup
        window.showPopup = function() {
            popupModal.classList.remove("opacity-0", "invisible");
            popupModal.classList.add("flex");
        }

        window.hidePopup = function() {
            popupModal.classList.add("opacity-0", "invisible");
            setTimeout(() => popupModal.classList.remove("flex"), 300);
        }

        // Chiudi il popup quando l'utente clicca su "Chiudi"
        if (closePopupBtn) {
            closePopupBtn.addEventListener("click", hidePopup);
        }
    });
</script>
