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

    <section class="mt-2 text-center">
        <h2 class="text-2xl font-bold text-green-400 flex items-center">
            <span class="flex-grow h-0.5 bg-white mr-2"></span>
            &#10022; Cerca Asteroide &#10022;
            <span class="flex-grow h-0.5 bg-white ml-2"></span>
        </h2>

        <div class="mb-4 text-[15px] text-white text-center mt-1">
            <p> Questa sezione consente di ricercare informazioni dettagliate su un asteroide o una cometa che si avvicina a
                un corpo celeste,
                <br> inserendo un ID o una denominazione, utilizzando i filtri, oppure combinando entrambi i metodi.
        </div>
    </section>

    <!-- FORM DI RICERCA -->
    <form id="formConLoader" action="{{route('asteroid.search')}}" method="POST" class="w-full max-w-7xl rounded-lg text-white">
        @csrf  {{-- Token CSRF per sicurezza --}}


        <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4 w-full justify-center">
            <!-- Input ricerca ID o Denominazione -->
            <div class="relative w-full sm:w-1/3">
                <!-- Icona dentro l'input, posizionata a sinistra -->
                <img src="media/icons/ricerca_nero.png" alt="Cerca Asteroide"
                     class="absolute left-3 top-1/2 transform -translate-y-1/2 w-6 h-6">

                <input type="search" name="search_query" placeholder="ID o denominazione"
                       class="w-full p-2 pl-10 rounded-2xl text-black focus:outline-none focus:ring-2 focus:ring-yellow-400">
            </div>

            <!-- Istruzioni -->
            <div id="instructionTrigger" class="flex items-center space-x-2 cursor-pointer">
                <img src="media/icons/documento.png" alt="Istruzioni" class="w-10 h-10">
                <a href="#" class="underline text-sm">Clicca qui per visualizzare le istruzioni</a>
            </div>
        </div>

        <!-- Pulsante Cerca centrato -->
        <div class="mt-4 flex justify-center">
            <button type="submit"
                    class="bg-yellow-500 hover:bg-yellow-600 text-black px-6 py-2 rounded-2xl flex items-center border-black border-2">
                Cerca
                <img src="media/icons/ricerca_nero.png" alt="Cerca Asteroide" class="w-6 h-6 ml-4">
            </button>
        </div>
        {{-- Errori di validazione --}}
        @if ($errors->any())
            <x-input-error class="mt-2 text-center" :messages="$errors->all()" />
        @endif

        {{-- Errore manuale da sessione
        @if (session('error'))
            <x-input-error class="mt-2 text-center" :messages="[session('error')]" />
        @endif

        @if(session('warning'))
            <x-input-error class="mt-2 text-center" :messages="[session('warning')]" />
        @endif
        --}}

        <!-- Sezione Filtri -->
        <div class="p-1 rounded-lg">
            <div class="text-sm text-white text-center shadow-black shadow-2xl mb-3">
                <p> Posiziona il cursore sopra i filtri per maggiori informazioni. (*) indica che quei filtri sono selezionabili solo da un utente registrato. </p>
            </div>

            <div class="grid grid-cols-3 gap-4 text-black text-[14px]">
                <!-- Filtri Base -->
                <div class="bg-white opacity-75 p-4 rounded-2xl border-black border-2 shadow-black shadow-2xl">
                    <div class="flex flex-col items-center">
                        <h3 class="font-extrabold mb-2 text-[18px] underline">Filtri Base</h3>
                    </div>

                    <!-- Data -->
                    <div class="grid grid-cols-2">
                        <div class="mt-4">
                            <label title = "Esclude i dati precedenti e successivi a questa data">
                                <input type="radio" name="date_filter" value="specific"> Data Specifica
                            </label><br>
                            <input type="date" name="specific_date" class="w-3/4 p-1 mt-1 rounded text-black">
                        </div>
                        <div>
                            <div class="mt-4">
                                <label title = "Esclude i dati con passaggio ravvicinato che non rientra in questo periodo temporale.">
                                    <input type="radio" name="date_filter" value="range"> Intervallo di Date
                                </label><br>
                                <div>
                                    <div class="flex items-center gap-2 mt-1 mb-2">
                                        <label>Da</label>
                                        <input type="date" name="date-min" min="1900-01-01" max="2100-01-01"
                                               class="w-3/4 p-1 rounded text-black" disabled>
                                    </div>
                                    <div class="flex items-center gap-2 mt-1">
                                        <label>A</label>
                                        <input type="date" name="date-max" min="1900-01-01" max="2100-01-01"
                                               class="w-3/4 p-1 rounded text-black ml-2" disabled>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Distanza -->
                    <div class="mt-4">
                        <div class="flex items-center gap-2">
                            <label title="Esclude dati con una distanza di approccio inferiore a questo valore.">Distanza Minima</label>
                            <input type="number" name="dist-min" min="0" step="0.01"
                                   class="w-1/3 ml-5 p-1 rounded text-black">
                            <select name="unit_min" class="p-1 rounded text-black border w-1/6">
                                <option value="km">km</option>
                                <option value="LD">LD</option>
                                <option value="UA">UA</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2 mt-3">
                            <label title="Esclude dati con una distanza di approccio superiore a questo valore.">Distanza Massima</label>
                            <input type="number" name="dist-min" min="0" step="0.01"
                                   class="w-1/3 ml-2 p-1 rounded text-black">
                            <select name="unit_max" class="p-1 rounded text-black border w-1/6">
                                <option value="km">km</option>
                                <option value="LD">LD</option>
                                <option value="UA">UA</option>
                            </select>
                        </div>
                    </div>


                    <div class="mt-2">
                        <label><input type="checkbox" name="pha"> SOLO Asteroidi Potenzialmente Pericolosi</label>
                    </div>
                </div>


                <!-- Filtri Avanzati -->
                <div class="bg-white opacity-75 p-4 rounded-2xl border-black border-2 shadow-black shadow-2xl"
                     @guest style="pointer-events: none; opacity: 0.5;" @endguest>
                    <div class="flex flex-col items-center mb-3">
                        <h3 class="font-extrabold mb-2 text-[18px] underline">Filtri Avanzati *</h3>
                    </div>

                    <!-- Magnitudine -->
                    <div class="grid grid-cols-2 gap-4 mb-2">
                        <div>
                            <label title="Esclude dati di oggetti con un valore H inferiore a questo.">Magnitudine assoluta minima</label>
                            <input type="number" name="h-min" min="0" step="0.01"
                                   class="w-full p-1 rounded text-black" @guest disabled @endguest>
                        </div>

                        <div>
                            <label title="Esclude dati di oggetti con un valore H superiore a questo.">Magnitudine assoluta massima</label>
                            <input type="number" name="h-max" min="0" step="0.01"
                                   class="w-full p-1 rounded text-black" @guest disabled @endguest>
                        </div>
                    </div>

                    <!-- Velocità -->
                    <div class="grid grid-cols-2 gap-4 mb-2">
                        <div>
                            <label title="Esclude dati con una velocità relativa inferiore a questo valore.">Velocità relativa minima (km/s)</label>
                            <input type="number" name="v-rel-min" min="0" step="0.01"
                                   class="w-full p-1 rounded text-black" @guest disabled @endguest>
                        </div>

                        <div>
                            <label title="Esclude dati con una velocità relativa superiore a questo valore.">Velocità relativa massima (km/s)</label>
                            <input type="number" name="v-rel-max" min="0" step="0.01"
                                   class="w-full p-1 rounded text-black" @guest disabled @endguest>
                        </div>
                    </div>

                    <label title="Limita i dati agli oggetti della classe orbitale specificata.">Classe di oggetti</label>
                    <select name="class" class="w-full p-1 rounded text-black" @guest disabled @endguest>
                        <option value="">Nessuna selezione</option>
                        <option value="IEO">IEO (Atira)</option>
                        <option value="ATE">ATE (Aten)</option>
                        <option value="APO">APO (Apollo)</option>
                        <option value="AMO">AMO (Amor)</option>
                        <option value="MCA">MCA (Mars-crossing Asteroid)</option>
                        <option value="IMB">IMB (Inner Main-belt Asteroid)</option>
                        <option value="MBA">MBA (Main-belt Asteroid)</option>
                        <option value="OMB">OMB (Outer Main-belt Asteroid)</option>
                        <option value="TJN">TJN (Jupiter Trojan)</option>
                        <option value="CEN">CEN (Centaur)</option>
                        <option value="TNO">TNO (TransNeptunian Object)</option>
                        <option value="PAA">PAA (Parabolic Asteroid)</option>
                        <option value="HYA">HYA (Hyperbolic Asteroid)</option>
                        <option value="HYP">HYP (Hyperbolic Comet)</option>
                        <option value="PAR">PAR (Parabolic Comet)</option>
                        <option value="COM">COM (Comet)</option>
                        <option value="JFC">JFC (Jupiter-family Comet*)</option>
                        <option value="HTC">HTC (Halley-type Comet*)</option>
                        <option value="ETc">ETc (Encke-type Comet)</option>
                        <option value="CTc">CTc (Chiron-type Comet)</option>
                        <option value="JFc">JFc (Jupiter-family Comet)</option>
                    </select>
                </div>

                <!-- Corpo Celeste -->
                <div
                    class="bg-white opacity-75 px-6 py-4 rounded-2xl border-black border-2 shadow-black shadow-2xl w-full max-w-lg mx-auto">
                    <div class="flex flex-col items-center">
                        <h3 class="font-extrabold mb-2 text-[18px] underline">Corpo Celeste</h3>
                    </div>

                    <div class="mb-4 text-m text-black text-center ">
                        <p> Limita la ricerca ad approcci ravvicinati al corpo selezionato. </p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-[16px] items-center ml-12 -mt-2">
                        <div>
                            <label><input type="radio" name="body" value="Merc"> Mercurio</label><br>
                            <label><input type="radio" name="body" value="Venus"> Venere</label><br>
                            <label><input type="radio" name="body" value="Earth"> Terra</label><br>
                            <label><input type="radio" name="body" value="Mars"> Marte</label><br>
                            <label><input type="radio" name="body" value="Juptr"> Giove</label><br>
                        </div>
                        <div>
                            <label><input type="radio" name="body" value="Satrn"> Saturno</label><br>
                            <label><input type="radio" name="body" value="Urnus"> Urano</label><br>
                            <label><input type="radio" name="body" value="Neptn"> Nettuno</label><br>
                            <label><input type="radio" name="body" value="Pluto"> Plutone</label><br>
                            <label><input type="radio" name="body" value="Moon"> Luna</label><br>
                        </div>
                    </div>

                </div>

            </div>

            {{--
            <div class="text-sm text-white text-center mt-2 mb-2 shadow-white shadow-2xl">
                <p> (*) indica che quei filtri sono selezionabili solo da un utente registrato. </p>
            </div>
            --}}
        </div>

    </form>


    <!-- Toast Notification -->
    @include('components.toastNotification')

    <!-- Loader -->
    @include('components.loader')

    <!-- Popup Modal -->
    @include('components.popUp.login-popUp')
    @include('components.popUp.searchInstruction-popUp')
</main>

</body>

{{-- @include('layouts.footer') --}}
</html>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const today = new Date().toISOString().split('T')[0];
        const specificDate = document.querySelector('input[name="specific_date"]');
        const dateRadios = document.querySelectorAll('input[name="date_filter"]');


        dateRadios.forEach(radio => {
            radio.addEventListener('change', function () {
                const minDate = document.querySelector('input[name="date-min"]');
                const maxDate = document.querySelector('input[name="date-max"]');

                if (this.value === "specific") {
                    specificDate.disabled = false;
                    specificDate.value = today;
                    minDate.disabled = true;
                    maxDate.disabled = true;
                } else if (this.value === "range") {
                    specificDate.disabled = true;
                    specificDate.value = "";
                    minDate.disabled = false;
                    maxDate.disabled = false;
                }
            });
        });

        // Imposta "Oggi" come valore iniziale se selezionato di default
        const todayRadio = document.querySelector('input[name="date_filter"][value="today"]');
        if (todayRadio.checked) {
            specificDate.value = today;
        }
    });

    // PopUp Istruzioni
    document.getElementById("instructionTrigger").addEventListener("click", function () {
        const modal = document.getElementById("InstructionPopupModal");
        modal.classList.remove("opacity-0", "invisible");
    });

    document.getElementById("closeInstructionPopupBtn").addEventListener("click", function () {
        const modal = document.getElementById("InstructionPopupModal");
        modal.classList.add("opacity-0", "invisible");
    });

</script>
