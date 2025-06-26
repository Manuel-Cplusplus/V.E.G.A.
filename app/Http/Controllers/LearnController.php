<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LearnContent;
use App\Models\LearnFeedback;
use App\Models\LearnPrompt;
use App\Models\LearnQuiz;
use App\Models\LearnStructure;
use App\Models\LLM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\CommonMark\CommonMarkConverter;

/**
 * V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
 * Copyright (c) 2025 Manuel Carlucci
 *
 * This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/4.0/
 */

class LearnController extends Controller
{

    /*
     * Estrae il contenuto e il quiz da un testo completo.
     *
     * @param string $fullText Testo completo
     * @return array Un array con 'content' e 'quiz'
     */
    public function splitContentAndQuiz(string $fullText): array
    {
        // Divide il testo esattamente alla parola "QUIZ"
        $parts = explode('QUIZ', $fullText, 2);

        return [
            'content' => trim($parts[0]),
            'quiz' => isset($parts[1]) ? trim($parts[1]) : '',
        ];
    }

    /*
     * Verifica se la struttura del quiz è valido.
     *
     * @param string $quizText Testo del quiz
     * @return bool True se il quiz è valido, false altrimenti
     */
    public function isValidQuiz(string $quizText): bool
    {
        // Match di tutte le domande (assumendo che inizino con "Domanda" o "Domanda\n")
        $questions = preg_split('/\bDomanda\b/i', $quizText, -1, PREG_SPLIT_NO_EMPTY);

        if (count($questions) !== 4) {
            return false; // Devono esserci esattamente 4 domande
        }

        foreach ($questions as $q) {
            // Conta il numero di risposte con etichetta A), B), C), D)
            preg_match_all('/^[ \t]*[ABCD]\)/m', $q, $matches);
            if (count($matches[0]) !== 4) {
                return false; // Ogni domanda deve avere 4 risposte
            }
        }

        return true;
    }

    /*
     * Estrae le domande e le risposte da un testo di quiz.
     *
     * @param string $quizText Testo del quiz
     * @return array Un array di domande e risposte
     */
    public function parseQuiz(string $quizText): array
    {
        $quizBlocks = preg_split('/\bDomanda\b/i', $quizText, -1, PREG_SPLIT_NO_EMPTY);
        $parsedQuiz = [];

        foreach ($quizBlocks as $block) {
            // Estrai righe pulite
            $lines = array_filter(array_map('trim', explode("\n", $block)));

            if (count($lines) < 5) continue; // Domanda + 4 risposte

            $question = array_shift($lines); // Prima riga = domanda
            $answers = [];

            foreach ($lines as $index => $line) {
                if (preg_match('/^[ABCD]\)\s*(.*)$/', $line, $matches)) {
                    $answers[] = [
                        'text' => $matches[1],
                        'is_correct' => $index === 0 // Solo la prima è corretta
                    ];
                }
            }

            // Solo se abbiamo 4 risposte valide
            if (count($answers) === 4) {
                $parsedQuiz[] = [
                    'question' => $question,
                    'answers' => $answers
                ];
            }
        }

        return $parsedQuiz;
    }

    /*
     * Mostra il contenuto di apprendimento per l'utente specificato, formattandone il testo.
     *
     * @param int $userId ID dell'utente
     * @param int $structureId ID della struttura di apprendimento
     * @return \Illuminate\View\View
     */
    /**
     * Mostra il contenuto didattico (originale o versione specifica).
     */
    public function showLearnContent($userId, $structureId, $versionId = null)
    {
        $user = Auth::user();

        if (!$user || $user->id != $userId) {
            abort(403, 'Accesso negato.');
        }

        // Recupera la struttura dell’utente
        $structure = LearnStructure::where('id', $structureId)
            ->where('user_id', $userId)
            ->firstOrFail();

        // Recupera il contenuto (versione richiesta o la prima)
        $content = $versionId
            ? LearnContent::findOrFail($versionId)
            : LearnContent::where('learn_structure_id', $structureId)->orderBy('version')->firstOrFail();

        // Recupera il modello LLM associato
        $llm = LLM::findOrFail($structure->LLMID);

        // Converte il contenuto da Markdown a HTML
        $converter = new CommonMarkConverter();
        $contentHtml = $converter->convert($content->content);

        // Applica stili personalizzati
        $contentHtml = preg_replace([
            '#<p>\s*<strong>(.*?)</strong>\s*</p>#',
            '#<p>#',
            '#<ul>#',
            '#<ol>#',
            '#<li>#',
        ], [
            '<h2 class="text-xl font-bold mt-2 mb-2 text-green-300">$1</h2>',
            '<p class="mb-4 text-base">',
            '<ul class="list-disc pl-6 mb-6 space-y-2">',
            '<ol class="list-decimal pl-6 mb-6 space-y-2">',
            '<li class="ml-2 pl-2">',
        ], $contentHtml);

        // Gestione versioni
        $currentVersion = $content->version;
        $originalContent = null;
        $childVersions = [];

        $childVersions = $this->getAllChildVersions($content);
        //dd($childVersions->toArray());

        $originalContent = $content->version == 1 ? null : $content->originalContent;

        $hasQuiz = LearnQuiz::where('learn_content_id', $content->id)->exists();

        // Prompt
        if ($currentVersion == 1) {
            $prompt = LearnPrompt::find(5);
            $finalPrompt = $prompt->prompt . $structure->content;
        } else {
            $prompt = LearnPrompt::find(6); // Prompt per rigenerazione
            $feedback = $structure->content; // supponiamo che il campo "content" contenga il feedback originale
            $finalPrompt = str_replace(
                ['{CONTENUTO_ORIGINALE}', '{FEEDBACK_UTENTE}'],
                [$originalContent->content, $feedback],
                $prompt->prompt
            );
        }

        return view('tools.learn.LearnContent', compact(
            'contentHtml', 'structure', 'llm', 'hasQuiz', 'content',
            'childVersions', 'originalContent', 'currentVersion', 'finalPrompt'
        ));
    }

    /**
     * Mostra il quiz associato a un contenuto didattico.
     */
    public function showQuiz($userId, $structureId, $contentId)
    {
        $user = Auth::user();

        if (!$user || $user->id != $userId) {
            abort(403, 'Accesso negato.');
        }

        $structure = LearnStructure::where('id', $structureId)
            ->where('user_id', $userId)
            ->firstOrFail();

        // Trova il contenuto giusto in base al contentId (versione)
        $content = LearnContent::where('id', $contentId)
            ->where('learn_structure_id', $structureId)
            ->firstOrFail();

        $llm = LLM::findOrFail($structure->LLMID);

        $quizzes = LearnQuiz::where('learn_content_id', $content->id)
            ->with('answers')
            ->get();

        return view('tools.learn.LearnQuiz', compact('structure', 'llm', 'quizzes'));
    }


    /**
     * Salva un feedback e genera nuova versione tramite LLM.
     */
    public function storeFeedback(Request $request)
    {
        $request->validate([
            'learn_content_id' => 'required|exists:learn_contents,id',
            'feedback' => 'required|string',
        ]);

        /*$feedback = LearnFeedback::create([
            'learn_content_id' => $request->learn_content_id,
            'feedback' => $request->feedback,
        ]);*/

        $originalContent = LearnContent::findOrFail($request->learn_content_id);
        $user = Auth::user();

        app(LLMController::class)->regenerateFromFeedback($originalContent, $request->feedback, $user);

        return redirect()->back()->with('success', 'Feedback inviato con successo! Una nuova versione è stata generata.');
    }

    /**
     * Elimina una struttura di apprendimento dell'utente.
     */
    public function destroy(LearnStructure $structure, LearnContent $version)
    {
        if ($version->version == 1) {
            // Cancella tutto
            $structure->delete(); // cascata con contenuti e quiz
        } else {
            $version->delete();
        }

        return redirect()->route('LearnList')->with('success', 'Contenuto eliminato con successo.');
    }


    /**
     * Recupera ricorsivamente tutte le versioni figlie di un contenuto didattico.
     */
    public function getAllChildVersions($content)
    {
        $children = $content->childVersions()->with('feedback')->get();

        foreach ($children as $child) {
            $child->children = $this->getAllChildVersions($child);
        }

        return $children;
    }
}
