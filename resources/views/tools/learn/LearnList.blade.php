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

    <section class="mt-5 text-center">
        <h2 class="text-2xl font-bold text-green-400 flex items-center">
            <span class="flex-grow h-0.5 bg-white mr-2"></span>
            &#10022; Lista Contenuti Learn &#10022;
            <span class="flex-grow h-0.5 bg-white ml-2"></span>
        </h2>

        <p class="text-white text-l font-semibold mt-2"> Qui troverai tutti i contenuti Learn da te generati in ordine di creazione dal più recente.</p>

        <!-- Collegamento alla Creazione Learn -->
        <div class="mt-4 text-center">
            <a href="{{route('CreateLearn')}}" class="text-yellow-300 hover:text-yellow-500 font-semibold transition duration-200">
                Crea nuovi Contenuti Learn
            </a>
        </div>

        @php
            $learns = \App\Models\LearnStructure::where('user_id', Auth::id())
                ->whereHas('contents', function ($query) {
                    $query->where('version', 1);
                })
                ->with(['contents' => function ($query) {
                    $query->where('version', 1);
                }])
                ->orderByDesc('created_at')
                ->get();
        @endphp


        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 p-6 w-full max-w-7xl">
            @foreach ($learns as $learn)
                <div
                    onclick="window.location='{{ route('learn.show', ['user' => $learn->user_id, 'structure' => $learn->id]) }}'"
                    class="relative bg-gray-800 border-2 border-green-400 hover:bg-gray-700 p-5 rounded-xl shadow-md transition duration-200 max-w-80 cursor-pointer"
                >
                    <!-- Icona Elimina -->
                    <button type="button"
                            onclick="event.stopPropagation(); confirmDelete({{ $learn->id }})"
                            class="text-red-500 hover:text-red-700 absolute top-2 right-2 z-10"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>

                    <!-- Contenuto -->
                    <h3 class="text-xl font-bold text-green-400 mb-2">
                        {{ \Illuminate\Support\Str::limit(strip_tags($learn->title), 50) }}
                    </h3>
                    <p class="text-white text-sm italic">
                        Richiesta:<br>
                        {{ \Illuminate\Support\Str::limit(strip_tags($learn->content), 100) }}
                    </p>
                    <p class="text-gray-400 text-xs mt-2">
                        Creato il: {{ $learn->created_at->format('d/m/Y H:i') }}
                    </p>

                    <!-- Form nascosto -->
                    <form id="delete-form-{{ $learn->id }}"
                          action="{{ route('learn.destroy', ['structure' => $learn->id, 'version' => $learn->contents->first()->id]) }}"
                          method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            @endforeach
        </div>


    </section>


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

<!-- Modale Alert Delete -->
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
