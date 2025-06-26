{{--
    V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
    Copyright (c) 2025 Manuel Carlucci

    Licensed under CC BY-NC-SA 4.0: http://creativecommons.org/licenses/by-nc-sa/4.0/
--}}

@php use Illuminate\Support\Facades\Auth; @endphp
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('layouts.head')

<body class="antialiased min-h-screen">
<!-- antialiased: Migliora la leggibilità del testo sullo schermo -->
<!-- min-h-screen: Imposta l'altezza minima del corpo (<body>) uguale all'altezza dello schermo (viewport). -->

@include('layouts.header')

<main class="flex flex-col items-center justify-center">

    <section class="w-full max-w-7xl mt-4 flex flex-col md:flex-row gap-10">

        <!-- Colonna sinistra -->
        <div class="w-full md:w-1/3 flex flex-col gap-4">

            <!-- Learn Structure -->
            <div class="w-full bg-gray-800 border border-green-400 px-6 py-2 rounded-xl shadow-lg h-fit">
                <h2 class="text-xl font-bold text-green-400 mb-2 text-center">{{ $structure->title }}</h2>
                <p class="text-gray-300 mb-2 text-[14px]">Richiesta: <br>
                    {!! nl2br(e($structure->content)) !!}
                    {{-- Spiegazione parametri:
                           - e(...): Escapes HTML special characters to prevent XSS attacks.
                           - nl2br(...): Converts newlines to <br> tags for proper HTML formatting.
                           - {!! !!}: Renders the HTML content without escaping it.
                       --}}
                </p>

                <!-- Prompt -->
                @if ($currentVersion == 1 || $currentVersion > 1)
                    <div class="text-center">
                        <button
                            class=" hover:bg-blue-700 text-white px-4 rounded-xl text-sm transition duration-200"
                            onclick="document.getElementById('promptModal').classList.remove('hidden')"
                        >
                            Vedi Prompt Usato per la Generazione
                        </button>
                    </div>
                @endif

                <div class="text-[12px] text-gray-400 mt-4 border-t pt-4 border-gray-700">
                    <p><strong>Creato il:</strong> {{ $structure->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>LLM Usato:</strong>
                    <ul class="list-disc list-inside ml-3">
                        <li>Provider: {{$llm->provider}}</li>
                        <li>Modello: {{$llm->model}}</li>
                    </ul>
                    </p>
                </div>
            </div>

            @if ($hasQuiz)
                <!-- Quiz -->
                <button
                    class="w-full border-black border-2 bg-green-400 p-2 rounded-xl shadow-lg h-fit hover:bg-green-500 transition duration-200"
                    onclick="window.location='{{ route('learn.quiz', ['user' => $structure->user_id, 'structure' => $structure->id, 'content' => $content->id]) }}'">
                    Testa le tue Conoscenze
                </button>
            @else
                <!-- Nessun quiz disponibile -->
                <div class="w-full bg-yellow-700 text-center text-black p-2 rounded-xl shadow-lg">
                    ⚠️ Nessun quiz disponibile per questo contenuto ⚠️.
                </div>
            @endif

            <!-- List -->
            <button
                class="w-full border-black border-2 bg-yellow-300 p-2 rounded-xl shadow-lg h-fit hover:bg-yellow-500 transition duration-200"
                onclick="window.location='{{route('LearnList')}}'">
                Torna alla Lista di Contenuti Didattici
            </button>

            <!-- Sezione Feedback -->
            <div class="w-full bg-gray-800 border border-yellow-400 px-6 py-2 rounded-xl shadow-lg">
                <h2 class="text-xl font-bold text-yellow-400 mb-2 text-center">Lascia un Feedback</h2>

                <form id="feedbackForm" action="{{ route('learn.feedback.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="learn_content_id" value="{{ $content->id }}">

                    <textarea name="feedback" rows="1" required
                              class=" text-[14px] w-full p-3 rounded-md bg-gray-700 text-white border border-gray-600 focus:outline-none focus:border-yellow-400"
                              placeholder="Richiedi una modifica da apportare..."></textarea>

                    <button type="button" onclick="submitForm()"
                            class="mt-1 w-full bg-yellow-400 hover:bg-yellow-500 text-black px-5 py-2 rounded-md shadow-md transition duration-200">
                        Invia Feedback
                    </button>
                </form>
            </div>
        </div>


        <!-- Learn Content -->
        <div id="content"
             class="w-full md:w-2/3 bg-gray-800 border border-green-400 p-6 rounded-xl shadow-lg max-h-[80vh] overflow-y-auto">

            <!-- Versioning Info -->
            <div class="w-full bg-gray-700 border border-yellow-400 p-4 rounded-xl shadow mb-4 text-[14px]">
                @if ($content->version == 1)
                    <p class="text-yellow-300 font-semibold">Versione originale del contenuto.</p>
                @else
                    <p class="text-yellow-300 font-semibold">
                        Stai visualizzando la <strong>Versione {{ $content->version }}</strong> di {{$structure->title}}.
                    </p>

                    @if ($originalContent)
                        <a href="{{ route('learn.show', ['user' => $structure->user_id, 'structure' => $structure->id, 'version' => $originalContent->id]) }}"
                           class="text-green-400 underline hover:text-green-300">
                            Passa alla Versione Precedente
                        </a>
                    @endif

                    <p class="text-white mt-2">
                        Feedback utilizzato per generare questa versione: <br>
                        <em>"{{ optional($content->feedback)->feedback ?? 'Non disponibile' }}"</em>
                    </p>
                @endif

                @if ($childVersions && count($childVersions) > 0)
                    <hr class="my-4 border-yellow-400">
                    <p class="text-white mt-2">Versioni derivate da questa:</p>

                    <ul class="mt-1 list-disc pl-5 text-gray-300 space-y-1">
                        @foreach ($childVersions as $version)
                            <li>
                                <details class="ml-2">
                                    <summary class="cursor-pointer">
                                        <a href="{{ route('learn.show', ['user' => $structure->user_id, 'structure' => $structure->id, 'version' => $version->id]) }}"
                                           class="text-green-400 underline hover:text-green-300">
                                            Versione {{ $version->version }}
                                        </a>
                                        - Feedback: <em>"{{ optional($version->feedback)->feedback ?? 'Nessun feedback' }}"</em>


                                        <!-- Pulsante eliminazione per child -->
                                        <form id="delete-form-{{ $version->id }}"
                                              action="{{ route('learn.destroy', ['structure' => $structure->id, 'version' => $version->id]) }}"
                                              method="POST" class="inline-flex items-center ml-2 align-middle">
                                            @csrf
                                            @method('DELETE')
                                            <!-- NOTA: type="button" per evitare submit automatico -->
                                            <button type="button"
                                            onclick="confirmDelete({{ $version->id }})"
                                                    class="text-red-500 hover:text-red-700 text-sm flex items-center"
                                                    title="Elimina">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </form>


                                    </summary>

                                    <!-- Versioni figlie ricorsive -->
                                    @if (isset($version->children) && count($version->children) > 0)
                                        @include('tools.learn.partials.child-version', ['versions' => $version->children, 'structure' => $structure])
                                    @endif
                                </details>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <!-- Contenuto Generato -->
            <h2 class="text-2xl font-bold text-green-400 flex items-center justify-between">
                Contenuto Generato
                <button id="printPdfBtn" class="text-white hover:text-green-300 flex items-center" title="Stampa in PDF">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                </button>
            </h2>
            <p class="text-white text-[14px] mb-4">Tieni presente che, come ogni modello linguistico, potrebbe commettere errori quindi assicurati di verificare le informazioni importanti.</p>


            <div id="content">
                <div class="text-white">
                    {!! $contentHtml !!}
                </div>
            </div>

        </div>

    </section>


    <!-- Toast Notification -->
    @include('components.toastNotification')

    <!-- Loader -->
    @include('components.loader')

    <!-- Popup Modal -->
    @include('components.popUp.login-popUp')
    @include('tools.learn.partials.promptModal')
</main>

</body>

{{-- @include('layouts.footer') --}}
</html>


<!-- CSS personalizzato per la scrollbar più sottile -->
<style>
    /* Personalizza la larghezza della scrollbar */
    #content::-webkit-scrollbar {
        width: 4px; /* Imposta la larghezza della scrollbar */
    }

    /* Colore e aspetto della parte della scrollbar che si muove */
    #content::-webkit-scrollbar-thumb {
        background-color: white;
        border-radius: 10px;
    }

    /* Colore della traccia della scrollbar */
    #content::-webkit-scrollbar-track {
        background-color: #1F2937;
    }
</style>



<script>
    /** Invio Form con loader **/
    function submitForm() {

        // Mostra il loading overlay
        document.getElementById('loadingOverlay').classList.add('flex');
        document.getElementById('loadingOverlay').classList.remove('hidden');

        // Invia il form
        const form = document.getElementById('feedbackForm');
        if (form) {
            form.submit();
        } else {
            console.error("Form con id 'feedbackForm' non trovato.");
        }

    }
</script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success mx-2",
                cancelButton: "btn btn-danger mx-2"
            },
            buttonsStyling: true
        });

        swalWithBootstrapButtons.fire({
            title: "Sei sicuro di voler eliminare il contenuto?",
            text: "Questa azione è irreversibile!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sì, elimina!",
            confirmButtonColor: '#22c55e',
            cancelButtonColor: '#ef4444',
            cancelButtonText: "No, annulla!",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire({
                    title: "Annullato",
                    text: "Il tuo contenuto è al sicuro!",
                    icon: "error"
                });
            }
        });
    }
</script>


<!-- Script per la funzionalità di stampa PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    document.getElementById('printPdfBtn').addEventListener('click', function() {
        // Mostra il loader
        document.getElementById('loadingOverlay').classList.add('flex');
        document.getElementById('loadingOverlay').classList.remove('hidden');

        // Identifichiamo il contenuto esatto da stampare
        const contentElements = document.querySelectorAll('#content .text-white');
        let contentHTML = '<p>Contenuto non trovato</p>';

        if (contentElements.length > 0) {
            // Usiamo l'ultimo elemento trovato (il più specifico) - fatto con "-1"
            const tempEl = document.createElement('div');
            tempEl.innerHTML = contentElements[contentElements.length - 1].innerHTML;

            // Rimuove classi Tailwind che alterano colori e font-size
            tempEl.querySelectorAll('*').forEach(el => {
                el.style.color = 'black';
                el.style.fontSize = '13px';
                el.style.fontFamily = 'Arial, sans-serif';
            });

            contentHTML = tempEl.innerHTML;
        } else {
            // Piano B: cerchiamo qualsiasi contenuto all'interno del div principale
            const mainContent = document.getElementById('content');
            if (mainContent) {
                contentHTML = mainContent.innerHTML;
            }
        }

        // Creiamo un elemento temporaneo che conterrà il contenuto da stampare
        const printContent = document.createElement('div');

        // Intestazione PDF con logo
        const header = document.createElement('div');
        const logoUrl = '{{ asset('media/logo/logo.png') }}';

        header.innerHTML = `
        <div style="text-align: center; margin-bottom: 20px; display: flex; align-items: center; justify-content: center;">
            <!-- Logo -->
            <div style="margin-top: 10px; padding: 0 20px;">
                <img src="${logoUrl}" alt="Logo" style="height: 64px; width: auto;">
            </div>

            <!-- Testo centrato -->
            <div style="text-align: center; margin-right: 20px;">
                <h1 style="font-size: 24px; font-weight: bold; color: #4ade80; margin: 0;">V.E.G.A.</h1>
                <h2 style="font-size: 18px; margin: 5px 0 0 0;">Visual Exploration and Graphical Analysis of Asteroids</h2>
            </div>
        </div>
    `;

        // Aggiungiamo i dettagli della struttura
        const structureDetails = document.createElement('div');
        structureDetails.innerHTML = `
        <div style="border: 1px solid black; padding: 10px; margin-bottom: 10px; border-radius: 10px;">
            <h2 style="font-size: 20px; font-weight: bold; color: black; text-align: center; margin-bottom: 10px;">{{ $structure->title }}</h2>
            <p><strong>Richiesta:</strong><br></p>
            <p style="font-size: 15px;">{{ $structure->content }}</p>

            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #374151;">
                <p style="font-size: 15px;"><strong>Creato il:</strong> {{ $structure->created_at->format('d/m/Y H:i') }}</p>
                <p style="font-size: 15px;"><strong>LLM Usato:</strong></p>
                <ul style="list-style-type: disc; margin-left: 20px; font-size: 15px;">
                    <li>Provider: {{$llm->provider}}</li>
                    <li>Modello: {{$llm->model}}</li>
                </ul>
            </div>
        </div>
    `;

        // Contenuto generato - Usando il contenuto trovato
        const contentDiv = document.createElement('div');
        contentDiv.innerHTML = `
        <div style="font-family: Arial, sans-serif; margin-top: 15px"; >${contentHTML}</div>
    `;

        // Aggiungiamo tutto al contenitore temporaneo
        printContent.appendChild(header);
        printContent.appendChild(structureDetails);
        printContent.appendChild(contentDiv);

        // Opzioni per html2pdf
        const opt = {
            margin:      [10, 10],
            filename:    '{{ $structure->title }}_VEGA.pdf',
            image:       { type: 'jpeg', quality: 0.95 },
            html2canvas: {
                scale: 2,
                useCORS: true,
                logging: true,
                letterRendering: true,
                // Parametri per documenti lunghi
                scrollX: 0,
                scrollY: 0,
                windowWidth: document.documentElement.offsetWidth,
                windowHeight: document.documentElement.offsetHeight
            },
            jsPDF:       {
                unit: 'mm',
                format: 'a4',
                orientation: 'portrait',
                compress: true,
                precision: 2
            },
            // Dividi automaticamente le pagine se il contenuto è troppo lungo
            pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
        };

        // Genera il PDF
        html2pdf().from(printContent).set(opt).save().then(() => {
            // Nascondi il loader quando il PDF è stato generato
            document.getElementById('loadingOverlay').classList.add('hidden');
            document.getElementById('loadingOverlay').classList.remove('flex');
        }).catch(error => {
            console.error("Errore nella generazione del PDF:", error);
            alert("Si è verificato un errore durante la generazione del PDF. Riprova più tardi.");
            document.getElementById('loadingOverlay').classList.add('hidden');
            document.getElementById('loadingOverlay').classList.remove('flex');
        });
    });
</script>
