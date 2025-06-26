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



<main class="flex flex-col items-center justify-center">

    <!-- Descrizione -->
    <section class="text-center mb-8 mt-6">
        <h2 class="text-2xl font-bold text-green-400 flex items-center">
            <span class="flex-grow h-0.5 bg-white mr-2"></span>
            &#10022; Cerca Incontri Ravvicinati con la Terra &#10022;
            <span class="flex-grow h-0.5 bg-white ml-2"></span>
        </h2>
        <p class="text-[16px] text-white">
            Questa sezione consente di ottenere un elenco di asteroidi in base alla loro data di massimo avvicinamento alla Terra. <br>
            L'utente potrà specificare un intervallo temporale, con una durata massima di 7 giorni, per individuare gli oggetti che avranno un incontro ravvicinato con il nostro pianeta.
        </p>
    </section>

    <!-- Form Calendario -->
    <form id="formConLoader" action="{{route('closeApproaches.search')}}" method="POST" class="w-full max-w-7xl rounded-lg text-white">
        @csrf  {{-- Token CSRF per sicurezza --}}

        <!-- Selettore Intervallo Date -->
        <div class="flex flex-col items-center justify-center" id="date-range-container">
            <label for="calendar-inline" class="text-white font-semibold mb-4">Seleziona un intervallo temporale</label>
            <div id="calendar-inline" class="mb-4 w-full max-w-2xl mx-auto rounded-2xl"></div>

            <input type="hidden" name="start_date" id="start_date">
            <input type="hidden" name="end_date" id="end_date">
        </div>


        <!-- Pulsante Cerca -->
        <div class="mt-4 flex justify-center">
            <button type="submit"
                    class="bg-yellow-500 hover:bg-yellow-600 text-black px-6 py-2 rounded-2xl flex items-center border-black border-2">
                Cerca
                <img src="media/icons/ricerca_nero.png" alt="Cerca Asteroide" class="w-6 h-6 ml-4">
            </button>
        </div>
    </form>


    <!-- Toast Notification -->
    @include('components.toastNotification')

    <!-- Loader -->
    @include('components.loader')

    <!-- Popup Modal -->
    @include('components.popUp.login-popUp')
</main>

</body>

{{-- @include('layouts.footer') --}}
</html>

<style>
    /* Stile per il selettore mese e anno */
    .flatpickr-current-month {
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 20px;
        font-weight: 700;
        color: #333;
        margin-bottom: 12px;
        background-color: #f9fafb;
        padding: 8px;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: background-color 0.2s ease-in-out;
    }

    /* Colore sfondo al passaggio del mouse */
    .flatpickr-current-month:hover {
        background-color: #f1f5f9;
    }

    /* Stile dei mesi */
    .flatpickr-months {
        padding: 10px 0;
        background-color: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        border-radius: 12px 12px 0 0;
    }

    /* Pulsanti di navigazione mese */
    .flatpickr-prev-month,
    .flatpickr-next-month {
        background-color: #e5e7eb;
        border-radius: 50%;
        padding: 10px;
        margin: 10px 12px 0; /* Aggiunto un margine superiore per abbassare i pulsanti */
        transition: background-color 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Ombra per i pulsanti */
    }

    .flatpickr-prev-month:hover,
    .flatpickr-next-month:hover {
        background-color: #d1d5db;
        transform: scale(1.1); /* Effetto di ingrandimento per l'hover */
    }

    .flatpickr-prev-month:hover,
    .flatpickr-next-month:hover {
        background-color: #d1d5db;
        transform: scale(1.1); /* Effetto di ingrandimento per l'hover */
    }

    /* Stile per i giorni del calendario */
    .flatpickr-day {
        border-radius: 8px;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .flatpickr-day:hover {
        background-color: #facc15 !important;
        color: #000 !important;
    }

    /* Giorni selezionati e intervallo di selezione */
    .flatpickr-day.selected,
    .flatpickr-day.startRange,
    .flatpickr-day.endRange,
    .flatpickr-day.inRange {
        background: #facc15 !important;
        color: #000 !important;
        border-radius: 8px !important;
    }

    /* Giorni disabilitati */
    .flatpickr-day.disabled {
        color: #9ca3af !important;
        background: transparent !important;
        cursor: not-allowed;
    }

    /* Evidenziazione del giorno corrente */
    .flatpickr-day.today:not(.selected):not(.inRange) {
        border: 1px solid #facc15;
        background-color: #fffbf0;
    }

</style>


<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let calendar = flatpickr("#calendar-inline", {
            inline: true,
            mode: "range",
            dateFormat: "Y-m-d",
            minDate: "1900-01-01", // Permetti la selezione di date a partire dal 1900
            showMonths: 2,

            onChange: function (selectedDates, dateStr, instance) {
                if (selectedDates.length === 1) {
                    const baseDate = selectedDates[0];

                    // Calcolo range minimo e massimo ammesso (7 giorni prima e dopo)
                    const minSelectable = new Date(baseDate);
                    minSelectable.setDate(baseDate.getDate() - 6); // Impostato su 6 per un intervallo di 7 giorni

                    const maxSelectable = new Date(baseDate);
                    maxSelectable.setDate(baseDate.getDate() + 6); // Impostato su 6 per un intervallo di 7 giorni

                    // Imposta range visivo limitato intorno alla prima data
                    instance.set('minDate', minSelectable);
                    instance.set('maxDate', maxSelectable);
                }

                if (selectedDates.length === 2) {
                    const diffTime = Math.abs(selectedDates[1] - selectedDates[0]);
                    const diffDays = diffTime / (1000 * 60 * 60 * 24) + 1;

                    if (diffDays > 7) {
                        instance.clear(); // Pulisce la selezione se oltre 7 giorni
                        alert("Puoi selezionare un intervallo massimo di 7 giorni.");
                        return;
                    }

                    // Salva le date selezionate
                    document.getElementById('start_date').value = instance.formatDate(selectedDates[0], "Y-m-d");
                    document.getElementById('end_date').value = instance.formatDate(selectedDates[1], "Y-m-d");
                }
            },

            onClose: function () {
                // Resetta i limiti ogni volta che si chiude il calendario
                this.set('minDate', "1900-01-01"); // Permetti sempre date precedenti al 1900
                this.set('maxDate', null);
            }
        });
    });
</script>





