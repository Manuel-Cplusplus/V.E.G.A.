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

    <section class="w-full max-w-7xl mt-5 flex flex-col md:flex-row gap-20">

        <!-- Colonna sinistra -->
        <div class="w-full md:w-1/4 flex flex-col gap-6">

            <!-- Learn Structure -->
            <div class="w-full bg-gray-800 border border-green-400 p-6 rounded-xl shadow-lg h-fit">
                <h2 class="text-2xl font-bold text-green-400 mb-4">{{ $structure->title }}</h2>
                <p class="text-gray-300 mb-6">Richiesta: <br>
                    {!! nl2br(e($structure->content)) !!}
                    {{-- Spiegazione parametri:
                           - e(...): Escapes HTML special characters to prevent XSS attacks.
                           - nl2br(...): Converts newlines to <br> tags for proper HTML formatting.
                           - {!! !!}: Renders the HTML content without escaping it.
                       --}}
                </p>

                <div class="text-sm text-gray-400 mt-4 border-t pt-4 border-gray-700">
                    <p><strong>Creato il:</strong> {{ $structure->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>LLM Usato:</strong>
                    <ul class ="list-disc list-inside ml-3">
                        <li>Provider: {{$llm->provider}}</li>
                        <li>Modello: {{$llm->model}}</li>
                    </ul>
                    </p>
                </div>
            </div>

            <!-- Content -->
            <button
                class="w-full border-black border-2 bg-green-400 p-4 rounded-xl shadow-lg h-fit hover:bg-green-500 transition duration-200"
                onclick="window.location='{{ route('learn.show', ['user' => $structure->user_id, 'structure' => $structure->id]) }}'">
                Torna al Contenuto
            </button>

            <!-- Controlla Risposte -->
            <button
                id="checkAnswersBtn"
                class="w-full border-black border-2 bg-blue-400 p-4 rounded-xl shadow-lg h-fit hover:bg-blue-500 transition duration-200">
                Controlla Risposte
            </button>

        </div>


        <!-- Learn Quiz -->
        <div id="quizContainer" class="w-full md:w-2/3 bg-gray-800 border border-green-400 p-6 rounded-xl shadow-lg max-h-[80vh] overflow-y-auto text-left text-white space-y-6">
            <h2 class="text-2xl font-bold text-green-400">Quiz Generato</h2>
            @foreach($quizzes as $quiz)
                <div class="quiz-question" data-quiz-id="{{ $quiz->id }}">
                    <p class="text-green-300 font-semibold">{{ $loop->iteration }}. {{ $quiz->question }}</p>

                    @php $shuffledAnswers = $quiz->answers->shuffle(); @endphp
                    @foreach($shuffledAnswers as $answer)
                        <label class="block mt-1">
                            <input type="radio" name="quiz[{{ $quiz->id }}]" value="{{ $answer->id }}" data-correct="{{ $answer->is_correct ? '1' : '0' }}" class="mr-2 answer-option">
                            {{ $answer->answer }}
                        </label>
                    @endforeach

                    <p class="feedback mt-2 font-semibold"></p>
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

<script>
    document.getElementById('checkAnswersBtn').addEventListener('click', () => {
        const questions = document.querySelectorAll('.quiz-question');

        questions.forEach(question => {
            const selected = question.querySelector('input[type="radio"]:checked');
            const feedback = question.querySelector('.feedback');

            if (!selected) {
                feedback.textContent = '⚠️ Seleziona una risposta.';
                feedback.className = 'feedback text-yellow-400 mt-2';
                return;
            }

            const isCorrect = selected.getAttribute('data-correct') === '1';

            feedback.textContent = isCorrect ? '✅ Corretto!' : '❌ Sbagliato.';
            feedback.className = isCorrect ? 'feedback text-green-400 mt-2' : 'feedback text-red-400 mt-2';
        });
    });
</script>



<!-- CSS personalizzato per la scrollbar più sottile -->
<style>
    /* Personalizza la larghezza della scrollbar */
    #quizContainer::-webkit-scrollbar {
        width: 4px; /* Imposta la larghezza della scrollbar */
    }

    /* Colore e aspetto della parte della scrollbar che si muove */
    #quizContainer::-webkit-scrollbar-thumb {
        background-color: white;
        border-radius: 10px;
    }

    /* Colore della traccia della scrollbar */
    #quizContainer::-webkit-scrollbar-track {
        background-color: #1F2937;
    }
</style>
