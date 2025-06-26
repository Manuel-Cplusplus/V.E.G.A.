<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LearnPromptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*DB::table('learn_prompts')->insert([
            'prompt' => 'Prova',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);*/

        /*DB::table('learn_prompts')->insert([
            'prompt' => "Scrivi un contenuto tecnico e chiaro sull'argomento che ti passerò.
                         Ricordati che dovrà essere relativo al tema 'Astronomia' e collegato.
                         Scrivi solo il contenuto tecnico, senza dire frasi come 'certamente, ecco a te' o frasi simili.
                         Alla fine del contenuto tecnico scrivi esattamente questa parola in maiuscolo 'QUIZ' e genera un
                         questionario di 4 domande in cui ogni domanda ha 4 risposte delle quali la prima è quella corretta, con questa struttura:
                         Domanda
                         A) Risposta 1
                         B) Risposta 2
                         C) Risposta 3
                         D) Risposta 4

                         Argomento:",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);*/

        /*DB::table('learn_prompts')->insert([
            'prompt' => "Rigenera il seguente contenuto tecnico sull'argomento che ti fornirò, tenendo conto del feedback che riceverai.
                 Il contenuto deve essere tecnico, chiaro e coerente con il tema 'Astronomia'.
                 Non scrivere frasi introduttive come 'Ecco a te', 'Certamente' o simili: fornisci solo il contenuto scientifico migliorato.

                 Alla fine del contenuto scrivi esattamente la parola 'QUIZ' (tutta in maiuscolo) e genera un questionario di 4 domande.
                 Ogni domanda deve avere esattamente 4 risposte: la **prima** deve essere quella corretta.
                 Rispetta rigorosamente questa struttura:
                 Domanda
                 A) Risposta 1
                 B) Risposta 2
                 C) Risposta 3
                 D) Risposta 4

                 Di seguito il contenuto originario e il feedback a cui dovrai attenerti per la riscrittura.",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);*/


        /*DB::table('learn_prompts')->insert([
            'prompt' => "Rigenera il seguente contenuto tecnico sull'argomento che ti fornirò, tenendo conto del feedback che riceverai.
                     Il contenuto deve essere tecnico, chiaro e coerente con il tema 'Astronomia'.
                     Non scrivere frasi introduttive come 'Ecco a te', 'Certamente' o simili: fornisci solo il contenuto scientifico migliorato.

                     Alla fine del contenuto scrivi esattamente la parola 'QUIZ' (tutta in maiuscolo) e genera un questionario di 4 domande.
                     Ogni domanda deve avere esattamente 4 risposte: la **prima** deve essere quella corretta.
                     Rispetta rigorosamente questa struttura:
                     Domanda
                     A) Risposta 1
                     B) Risposta 2
                     C) Risposta 3
                     D) Risposta 4

                     Di seguito il contenuto originario e il feedback a cui dovrai attenerti per la riscrittura.

                     Contenuto originale:
                     {CONTENUTO_ORIGINALE}

                     Feedback ricevuto:
                     {FEEDBACK_UTENTE}",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);*/

        DB::table('learn_prompts')->insert([
            'prompt' => "Scrivi un contenuto tecnico e chiaro sull'argomento che ti passerò.
                         Ricordati che dovrà essere relativo al tema 'Astronomia' e collegato.
                         Assicurati che il contenuto e le domande siano privi di stereotipi culturali, di genere o etnici, e che siano adatti a un pubblico universale e inclusivo.
                         Scrivi solo il contenuto tecnico, senza dire frasi come 'certamente, ecco a te' o frasi simili.
                         Alla fine del contenuto tecnico scrivi esattamente questa parola in maiuscolo 'QUIZ' e genera un
                         questionario di 4 domande in cui ogni domanda ha 4 risposte delle quali la prima è quella corretta, con questa struttura:
                         Domanda
                         A) Risposta 1
                         B) Risposta 2
                         C) Risposta 3
                         D) Risposta 4

                         Argomento:",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('learn_prompts')->insert([
            'prompt' => "Rigenera il seguente contenuto tecnico sull'argomento che ti fornirò, tenendo conto del feedback che riceverai.
                     Il contenuto deve essere tecnico, chiaro e coerente con il tema 'Astronomia'.
                     Assicurati che il contenuto e le domande siano privi di stereotipi culturali, di genere o etnici, e che siano adatti a un pubblico universale e inclusivo.
                     Non scrivere frasi introduttive come 'Ecco a te', 'Certamente' o simili: fornisci solo il contenuto scientifico migliorato.

                     Alla fine del contenuto scrivi esattamente la parola 'QUIZ' (tutta in maiuscolo) e genera un questionario di 4 domande.
                     Ogni domanda deve avere esattamente 4 risposte: la **prima** deve essere quella corretta.
                     Rispetta rigorosamente questa struttura:
                     Domanda
                     A) Risposta 1
                     B) Risposta 2
                     C) Risposta 3
                     D) Risposta 4

                     Di seguito il contenuto originario e il feedback a cui dovrai attenerti per la riscrittura.

                     Contenuto originale:
                     {CONTENUTO_ORIGINALE}

                     Feedback ricevuto:
                     {FEEDBACK_UTENTE}",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
