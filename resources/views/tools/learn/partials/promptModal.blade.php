{{--
    V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
    Copyright (c) 2025 Manuel Carlucci

    Licensed under CC BY-NC-SA 4.0: http://creativecommons.org/licenses/by-nc-sa/4.0/
--}}

<div id="promptModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden">
    <div class="bg-gray-900 border border-green-400 text-white rounded-xl p-6 w-[90%] max-w-3xl shadow-2xl relative">
        <h3 class="text-xl font-bold text-green-400 mb-4">Prompt Generato</h3>

        <div id="scrollablePrompt" class="bg-gray-800 p-4 rounded text-sm overflow-auto max-h-[400px] text-green-200 whitespace-pre-line break-all font-mono">
            {{ $finalPrompt }}
        </div>

        <button
            class="absolute top-3 right-3 text-gray-300 hover:text-white"
            onclick="document.getElementById('promptModal').classList.add('hidden')"
        >
            ✕
        </button>
    </div>
</div>




<!-- CSS personalizzato per la scrollbar più sottile -->
<style>
    /* Personalizza la larghezza della scrollbar */
    #scrollablePrompt::-webkit-scrollbar {
        width: 4px; /* Imposta la larghezza della scrollbar */
    }

    /* Colore e aspetto della parte della scrollbar che si muove */
    #scrollablePrompt::-webkit-scrollbar-thumb {
        background-color: white;
        border-radius: 10px;
    }

    /* Colore della traccia della scrollbar */
    #scrollablePrompt::-webkit-scrollbar-track {
        background-color: #1F2937;
    }
</style>
