<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LearnAnswer;
use App\Models\LearnContent;
use App\Models\LearnFeedback;
use App\Models\LearnPrompt;
use App\Models\LearnQuiz;
use App\Models\LearnStructure;
use App\Models\LLM;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


/**
 * V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
 * Copyright (c) 2025 Manuel Carlucci
 *
 * This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/4.0/
 */


class LLMController extends Controller
{

    /*
     * Metodo per gestire le richieste di chat
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chat(Request $request)
    {
        Log::info('Richiesta ricevuta', [
            'headers' => $request->header(),
            'isAjax' => $request->ajax(),
            'content' => $request->all()
        ]);

        try {
            $validated = $this->validateRequest($request);

            $userMessage = $validated['message'];
            $pageContent = $request->input('page_content');
            $isSystemMessage = $request->input('is_system_message') === 'true'; // invisibile all'utente

            $endpoint = $this->getGeminiEndpoint();
            $history = session()->get('chat_history', []);

            if ($pageContent) {
                $history = $this->processPageContext($pageContent, $history);

                if ($isSystemMessage) {
                    session()->put('chat_history', $history);
                    return response()->json(['reply' => 'Contesto ricevuto']);
                }
            }

            $history[] = ['role' => 'user', 'parts' => [['text' => $userMessage]]];
            $reply = $this->callGeminiApi($endpoint, $history);

            $history[] = ['role' => 'model', 'parts' => [['text' => $reply]]];
            session()->put('chat_history', $history);

            return response()->json(['reply' => $reply]);

        } catch (\Exception $e) {
            Log::error('Eccezione nel metodo chat', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Errore del server: ' . $e->getMessage()], 500);
        }
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'message' => 'required|string',
            'page_content' => 'nullable|string',
            'is_system_message' => 'nullable|string',
        ]);
    }

    /*
     * Ottiene l'endpoint Gemini dal database
     * @return string
     */
    private function getGeminiEndpoint(): string
    {
        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) {
            throw new \Exception('API key non configurata. Contattare vega.astroproject@gmail.com.');
        }

        $llm = Llm::where('provider', 'Google')->where('model', 'gemini-2.0-flash')->first();
        if (!$llm) {
            throw new \Exception('Modello LLM non trovato nel database.');
        }

        return $llm->endpoint . "?key={$apiKey}";
    }

    /*
     * Elimina i tag HTML non necessari e gestisce il contesto della pagina
     * @param string $pageContent
     * @param array $history
     * @return array
     */
    private function processPageContext(string $pageContent, array $history): array
    {
        // Rimuovi i tag HTML non necessari per evitare di inviare troppi dati al modello.
        $pageContent = strip_tags($pageContent, '<h1><h2><h3><h4><h5><h6><p><div><span><table><tr><td><th><ul><li><ol><a><b><strong><i><em>');
        $lastContext = session()->get('last_page_context', '');

        // Se il contesto della pagina è lo stesso dell'ultimo, non fare nulla - evita di inviare lo stesso contesto al modello.
        if ($lastContext === $pageContent) {
            return $history;
        }

        session()->put('last_page_context', $pageContent);

        $contextMessage = [
            'role' => 'user',
            'parts' => [['text' => "Contesto HTML della pagina corrente:\n\n" . $pageContent . "\n\nUsa questo contesto per rispondere alle domande dell'utente. Non menzionare esplicitamente che hai ricevuto questo contesto HTML a meno che non ti venga chiesto."]]
        ];

        $systemResponse = [
            'role' => 'model',
            'parts' => [['text' => "Ho ricevuto il nuovo contesto della pagina e lo utilizzerò per fornirti risposte più pertinenti."]]
        ];

        $filteredHistory = $this->filterContextMessages($history);

        array_unshift($filteredHistory, $contextMessage);
        $filteredHistory[] = $systemResponse;

        return $filteredHistory;
    }

    /*
     * Filtra i messaggi di contesto per evitare di accumulare troppi messaggi simili nel tempo.
     * @param array $history
     * @return array
     */
    private function filterContextMessages(array $history): array
    {
        $filteredHistory = [];
        $isContextMessage = false;

        foreach ($history as $message) {
            // Scansiona lo storico e rimuove messaggi "vecchi" relativi al contesto HTML per evitare di accumulare troppi messaggi simili nel tempo.
            if ($message['role'] === 'user' &&
                isset($message['parts'][0]['text']) &&
                strpos($message['parts'][0]['text'], 'Contesto HTML della pagina') === 0) {
                $isContextMessage = true;
                continue;
            }

            if ($isContextMessage &&
                $message['role'] === 'model' &&
                isset($message['parts'][0]['text']) &&
                strpos($message['parts'][0]['text'], 'Ho ricevuto il contesto') === 0) {
                $isContextMessage = false;
                continue;
            }

            $filteredHistory[] = $message;
        }

        return $filteredHistory;
    }

    /*
     * Effettua la chiamata all'API Gemini
     * @param string $endpoint
     * @param array $history
     * @return string
     */
    private function callGeminiApi(string $endpoint, array $history): string
    {
        $payload = [
            'contents' => $history,
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 800,
            ]
        ];

        $response = Http::timeout(30)->post($endpoint, $payload);

        Log::info('API Response', [
            'statusCode' => $response->status(),
            'headers' => $response->headers(),
            'body' => $response->json() ?? $response->body(),
        ]);

        if ($response->successful()) {
            $data = $response->json();

            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                return $data['candidates'][0]['content']['parts'][0]['text'];
            }

            Log::warning('Struttura di risposta inattesa', ['response' => $data]);
            throw new \Exception('Struttura di risposta inattesa. Controlla i log per i dettagli.');
        }

        $error = $response->json()['error']['message'] ?? 'Errore sconosciuto';
        Log::error('Errore API Gemini', ['status' => $response->status(), 'error' => $error]);

        throw new \Exception("Errore API: $error");
    }


    /**
     * Resetta la sessione della chat
     */
    public function resetChat()
    {
        session()->forget('chat_history');
        return response()->json(['message' => 'Conversazione resettata']);
    }


    /**
     * Testa endpoint per debugging della connessione API
     */
    public function testConnection()
    {
        try {
            $apiKey = env('GEMINI_API_KEY');

            if (!$apiKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'API key is missing'
                ], 500);
            }

            $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}";

            $payload = [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => 'Ciao, come stai?']
                        ]
                    ]
                ]
            ];

            $response = Http::timeout(30)->post($endpoint, $payload);

            return response()->json([
                'success' => $response->successful(),
                'status' => $response->status(),
                'response' => $response->json()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }



    /* Metodo per generare contenuti di apprendimento
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generateLearnContent(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'request' => 'required',
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('homepage')->with('error', 'Utente non autenticato.');
        }

        $prompt = LearnPrompt::find(5);
        $finalPrompt = $prompt . $request->input('request');

        // Endpoint Gemini
        $endpoint = $this->getGeminiEndpoint();

        // Formattazione compatibile Gemini
        $history = [
            [
                'role' => 'user',
                'parts' => [
                    ['text' => $finalPrompt]
                ]
            ]
        ];


        $attempts = 0;
        $maxAttempts = 10;

        do {
            $aiResponse = $this->callGeminiApi($endpoint, $history);
            $sections = (new LearnController)->splitContentAndQuiz($aiResponse);
            $isValid = (new LearnController)->isValidQuiz($sections['quiz']);
            //dump($sections['content'], $sections['quiz']);
            $attempts++;
        } while (!$isValid && $attempts < $maxAttempts);

        /*if (!$isValid) {
            return back()->with('error', 'Il modello non ha generato un contenuto valido dopo diversi tentativi.');
        }*/

        // dd($sections['content'], $sections['quiz']);

        // LLM attivo
        $llm = Llm::where('provider', 'Google')->where('model', 'gemini-2.0-flash')->firstOrFail();

        // Salva struttura base
        $structure = LearnStructure::create([
            'title' => $request->title,
            'content' => $request->input('request'),
            'LLMID' => $llm->id,
            'learn_prompt_id' => $prompt->id,
            'user_id' => $user->id,
        ]);

        // Salva contenuto associato
        $content = LearnContent::create([
            'content' => $sections['content'],
            'learn_structure_id' => $structure->id,
        ]);

        if ($isValid) {
            // Estrai quiz strutturato e salva
            $parsedQuiz = (new LearnController)->parseQuiz($sections['quiz']);

            foreach ($parsedQuiz as $quizItem) {
                $quiz = LearnQuiz::create([
                    'question' => $quizItem['question'],
                    'learn_content_id' => $content->id,
                ]);

                foreach ($quizItem['answers'] as $answer) {
                    LearnAnswer::create([
                        'answer' => $answer['text'],
                        'is_correct' => (bool) $answer['is_correct'],
                        'learn_quiz_id' => $quiz->id,
                    ]);
                }
            }

            return redirect()->route('LearnList')->with('success', 'Contenuto e quiz generati e salvati con successo!');
        }else {

            // Se il quiz non è valido ma il contenuto sì
            return redirect()->route('LearnList')->with('warning', 'Contenuto salvato, ma il quiz non è stato generato correttamente.');
        }
    }


    /* Metodo per rigenerare contenuti di apprendimento tramite un feedback utente
         * @param Request $request
         * @return \Illuminate\Http\RedirectResponse
    */
    public function regenerateFromFeedback(LearnContent $originalContent, string $feedback, User $user)
    {
        $prompt = LearnPrompt::find(6); // Prompt per rigenerazione da feedback

        $finalPrompt = str_replace(
            ['{CONTENUTO_ORIGINALE}', '{FEEDBACK_UTENTE}'],
            [$originalContent->content, $feedback],
            $prompt->prompt
        );

        $endpoint = $this->getGeminiEndpoint();

        $history = [
            [
                'role' => 'user',
                'parts' => [
                    ['text' => $finalPrompt]
                ]
            ]
        ];

        $attempts = 0;
        $maxAttempts = 10;

        do {
            $aiResponse = $this->callGeminiApi($endpoint, $history);
            $sections = (new LearnController)->splitContentAndQuiz($aiResponse);
            $isValid = (new LearnController)->isValidQuiz($sections['quiz']);
            $attempts++;
        } while (!$isValid && $attempts < $maxAttempts);

        $llm = Llm::where('provider', 'Google')->where('model', 'gemini-2.0-flash')->firstOrFail();

        $nextVersion = LearnContent::where('learn_structure_id', $originalContent->learn_structure_id)
                ->max('version') + 1;

        // Nuovo contenuto generato
        $newContent = LearnContent::create([
            'content' => $sections['content'],
            'learn_structure_id' => $originalContent->learn_structure_id,
            'version' => $nextVersion,
            'original_content_id' => $originalContent->id,
        ]);

        // Salva il feedback associandolo al nuovo contenuto
        LearnFeedback::create([
            'learn_content_id' => $newContent->id,
            'feedback' => $feedback,
        ]);

        // Salva quiz se valido
        if ($isValid) {
            $parsedQuiz = (new LearnController)->parseQuiz($sections['quiz']);

            foreach ($parsedQuiz as $quizItem) {
                $quiz = LearnQuiz::create([
                    'question' => $quizItem['question'],
                    'learn_content_id' => $newContent->id,
                ]);

                foreach ($quizItem['answers'] as $answer) {
                    LearnAnswer::create([
                        'answer' => $answer['text'],
                        'is_correct' => (bool) $answer['is_correct'],
                        'learn_quiz_id' => $quiz->id,
                    ]);
                }
            }
        }

        return true;
    }


}
