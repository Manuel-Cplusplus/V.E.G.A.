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

    <div class="flex flex-row gap-5 px-8 mt-4">

        <!-- Box Testo -->
        <div class="bg-gray-200 text-black px-6 rounded-xl w-5/6 justify-between opacity-85 shadow-[8px_0_15px_rgba(0,0,0,0.8),-8px_0_15px_rgba(0,0,0,0.8)]">
            <h1 class="text-2xl font-extrabold mt-2 text-center text-indigo-700 underline tracking-wide cursor-pointer" onclick = window.location.href='{{ route('homepage') }}'>V.E.G.A.</h1>
            <h2 class="text-center text-sm italic text-gray-700">Visual Exploration and Graphical Analysis of Asteroids</h2>

            <div class="text-center text-[13px] mb-2 flex-row flex justify-center">
                © 2025 • Questo progetto è distribuito con Licenza
                <a href="https://creativecommons.org/licenses/by-nc-sa/4.0/" target="_blank" class=" ml-2 mr-2 underline text-blue-800 hover:text-blue-600">
                    CC BY-NC-SA 4.0
                </a>
                <img class="ml-4" src="https://mirrors.creativecommons.org/presskit/icons/cc.svg" style="max-width: 1em; max-height:1em; margin-left: .2em;">
                <img src="https://mirrors.creativecommons.org/presskit/icons/by.svg" style="max-width: 1em; max-height:1em; margin-left: .2em;">
                <img src="https://mirrors.creativecommons.org/presskit/icons/nc.svg" style="max-width: 1em; max-height:1em; margin-left: .2em;">
                <img src="https://mirrors.creativecommons.org/presskit/icons/sa.svg" style="max-width: 1em; max-height:1em; margin-left: .2em;">
            </div>

            <em class="block text-center font-semibold text-[14px] text-gray-800 mb-4">
                Progetto di Laurea Triennale in ICD <br>
                Relatori: Prof.ssa Vita Santa Barletta e Prof. Antonio Piccinno
            </em>

            <div class="text-justify leading-relaxed text-[13px]">
                <p>
                    Il progetto <strong>V.E.G.A.</strong> nasce per fornire uno strumento
                    intuitivo e completo per l’esplorazione, il confronto e la comprensione visiva dei dati legati agli asteroidi potenzialmente pericolosi.
                    Il nome richiama simbolicamente la stella Vega, una delle più luminose del cielo notturno, rappresentando l’obiettivo del progetto:
                    fare luce su un tema di grande rilevanza scientifica e divulgativa attraverso strumenti interattivi e accessibili.
                </p>

                <p class = "mt-3">
                    Il sistema è rivolto a un pubblico eterogeneo: appassionati di astronomia, studenti universitari, docenti, e più in generale
                    a chi desidera avvicinarsi alla divulgazione scientifica. <br>
                    Il sistema non è pensato come uno strumento specialistico per la ricerca avanzata, ma mira a fornire una piattaforma intuitiva
                    per l’accesso a dati aggiornati, offrendo un primo punto di riferimento per l’analisi e il monitoraggio degli asteroidi.
                </p>
                <p class = "mt-3">
                    Le funzionalità principali includono:
                </p>
                <ul class="list-disc ml-6 mt-2">
                    <li>Consultazione e confronto dei dati su asteroidi.</li>
                    <li>Integrazione dei servizi NASA: API NeoWS, CAD, Sentry, Fireball.</li>
                    <li>Visualizzazione di incontri ravvicinati con la Terra o atri corpi celesti.</li>
                    <li>Ricerca di Asteroidi con incontro ravvicinato con la Terra tramite id/denominazione o tramite alcune sue caratteristiche.</li>
                    <li>Visualizzazione di impatti atmosferici registrati.</li>
                    <li>Visualizzazione di possibili impatti futuri.</li>
                    <li>Visualizzazione 3D interattiva di Terra, Luna e asteroidi per visualizzare la distanza dell'asteroide dalla Terra in scala.</li>
                    <li>Analisi predittiva basata su dati storici e modelli di previsione degli impatti futuri.</li>
                    <li>Interpretazione semplificata dei dati tramite modelli di intelligenza artificiale, per rendere le informazioni accessibili anche a utenti non esperti.</li>
                    <li>Notifiche e monitoraggio personalizzato sugli aggiornamenti degli oggetti preferiti.</li>
                </ul>
                <br>
            </div>
        </div>



        <!-- Box Laterale con info Sviluppatore -->
        <div class="justify-items-center cursor-auto">
            <div>
                <h2 class="text-xl font-bold text-white text-center mb-2">Progettato e<br>Sviluppato da:</h2>

                <!-- Contenitore Foto + Info -->
                <div class="group relative flex flex-col items-center transition-all duration-300">

                    <!-- Immagine con bordo multicolore e overlay -->
                    <div class="relative p-1 rounded-2xl bg-gradient-to-r from-green-500 via-red-500 to-blue-500">
                        <img src="{{ asset('media/images/stakeholders/Manuel-trimmed.jpg') }}"
                             alt="Manuel Carlucci"
                             class="rounded-2xl h-52 object-contain border-white border-2" />

                        <!-- Overlay oscurante -->
                        <div class="absolute top-1 left-1 right-1 bottom-1 rounded-2xl bg-black/50 group-hover:bg-black/0 transition-all duration-500 pointer-events-none"></div>
                    </div>

                    <!-- Box Info (inizialmente compatto, si espande al passaggio del mouse) -->
                    <div class="overflow-hidden bg-gray-100 text-black text-sm w-64 mt-0.5 rounded-2xl transition-all duration-500 ease-in-out max-h-10 group-hover:max-h-80 px-4 py-2 opacity-85 shadow-[8px_0_15px_rgba(0,0,0,0.8),-8px_0_15px_rgba(0,0,0,0.8)]">
                        <div class="transition-all duration-500 ease-in-out">
                            <p class="font-semibold text-center text-[16px] underline">Manuel Carlucci</p>
                            <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-500 mt-2 space-y-1">
                                <p><strong>Email:</strong> m.carlucci69@studenti.uniba.it</p>
                                <p><strong>Studente di:</strong><br>Informatica e Comunicazione Digitale (Uniba - Sede Taranto)</p>
                                <p><strong>Ruolo:</strong> Analista dei requisiti, Progettista, Sviluppatore</p>
                                <p><strong>Competenze:</strong><br>Laravel, Tailwind CSS, HTML, CSS, JavaScript, Python, C, C++, Visual Basic, SQL e Matlab</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Loghi -->
        {{-- Info Generali
        - target="_blank": apre in una nuova scheda.
        - rel="noopener noreferrer": migliora la sicurezza.
        --}}
        <div class = "flex flex-col">
            <!-- Logo Università -->
            <img src="{{ asset('media/logo/uniba-logo.png') }}" alt="Logo Università" class="w-16 h-16 mt-16 ml-2 cursor-pointer justify-items-center flex hover:scale-110 transition-transform duration-200"
                 onclick="window.location.href='https://www.uniba.it/it/corsi/cdl-informatica-comunicazione-digitale-taranto/corso-di-laurea-triennale-in-informatica-e-comunicazione-digitale'" title="Corso ICD">

            <!-- Logo Sistema -->
            <img src="{{ asset('media/logo/logo.png') }}" alt="Logo Sistema" class="w-20 mr-4 cursor-pointer justify-items-center flex hover:scale-110 transition-transform duration-200"
                 onclick = window.location.href='{{ route('homepage') }}' title="Sistema V.E.G.A." >

            <!-- Logo Github -->
            <a href="https://github.com/Manuel-Cplusplus/" target="_blank" rel="noopener noreferrer" title="Vai alla repository GitHub">
                <svg fill = "white" height="65" width="65" aria-hidden="true" viewBox="0 0 24 24" version="1.1" data-view-component="true" class="ml-2 octicon octicon-mark-github v-align-middle hover:scale-110 transition-transform duration-200">
                    <path d="M12 1C5.9225 1 1 5.9225 1 12C1 16.8675 4.14875 20.9787 8.52125 22.4362C9.07125 22.5325 9.2775 22.2025 9.2775 21.9137C9.2775 21.6525 9.26375 20.7862 9.26375 19.865C6.5 20.3737 5.785 19.1912 5.565 18.5725C5.44125 18.2562 4.905 17.28 4.4375 17.0187C4.0525 16.8125 3.5025 16.3037 4.42375 16.29C5.29 16.2762 5.90875 17.0875 6.115 17.4175C7.105 19.0812 8.68625 18.6137 9.31875 18.325C9.415 17.61 9.70375 17.1287 10.02 16.8537C7.5725 16.5787 5.015 15.63 5.015 11.4225C5.015 10.2262 5.44125 9.23625 6.1425 8.46625C6.0325 8.19125 5.6475 7.06375 6.2525 5.55125C6.2525 5.55125 7.17375 5.2625 9.2775 6.67875C10.1575 6.43125 11.0925 6.3075 12.0275 6.3075C12.9625 6.3075 13.8975 6.43125 14.7775 6.67875C16.8813 5.24875 17.8025 5.55125 17.8025 5.55125C18.4075 7.06375 18.0225 8.19125 17.9125 8.46625C18.6138 9.23625 19.04 10.2125 19.04 11.4225C19.04 15.6437 16.4688 16.5787 14.0213 16.8537C14.42 17.1975 14.7638 17.8575 14.7638 18.8887C14.7638 20.36 14.75 21.5425 14.75 21.9137C14.75 22.2025 14.9563 22.5462 15.5063 22.4362C19.8513 20.9787 23 16.8537 23 12C23 5.9225 18.0775 1 12 1Z"></path>
                </svg>
            </a>

            <!-- Logo Linkedin -->
            <a href="https://www.linkedin.com/in/manuel-carlucci-1947a026b" target="_blank" rel="noopener noreferrer" title="Vai al profilo LinkedIn">
                <svg fill="white" height="73" width="73" aria-hidden="true" viewBox="0 0 24 24" version="1.1" class="ml-1 hover:scale-110 transition-transform duration-200 rounded-full p-2">
                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                </svg>
            </a>

            <!-- Logo ORCID -->
            <a href="https://orcid.org/0009-0000-6213-7051" target="_blank" rel="noopener noreferrer" title="Vai al profilo ORCID">
                <svg fill="white" height="65" width="65" aria-hidden="true" viewBox="0 0 24 24" version="1.1" class="ml-2 hover:scale-110 transition-transform duration-200 rounded-full bg-green-600 p-2">
                    <path d="M12 0C5.372 0 0 5.372 0 12s5.372 12 12 12 12-5.372 12-12S18.628 0 12 0zM7.369 4.378c.525 0 .947.431.947.947 0 .525-.422.947-.947.947-.525 0-.946-.422-.946-.947 0-.525.421-.947.946-.947zm-.722 3.038h1.444v10.041H6.647V7.416zm3.562 0h3.9c3.712 0 5.344 2.653 5.344 5.025 0 2.578-2.016 5.016-5.325 5.016h-3.919V7.416zm1.444 1.303v7.444h2.297c2.359 0 3.667-1.394 3.667-3.722 0-2.016-1.313-3.722-3.667-3.722h-2.297z"/>
                </svg>
            </a>

        </div>

    </div>

    <!-- Popup Modal -->
    @include('components.popUp.login-popUp')
</main>

</body>

{{-- @include('layouts.footer') --}}
</html>

