<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\NasaApiService;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
 * Copyright (c) 2025 Manuel Carlucci
 *
 * This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/4.0/
 */

class SentryController extends Controller
{
    public function searchSentryElements(Request $request)
    {

        // Recupera i valori dei filtri dal form
        $ipMinInput = $request->input('ip-min');
        $diamMin = $request->input('diam-min');
        $dateMin = $request->input('date-min');
        $dateMax = $request->input('date-max');

        $queryParams = [];

        // Conversione della probabilità da % a valore decimale (es. 1% -> 0.01)
        if (!empty($ipMinInput)) {
            $ipDecimal = floatval($ipMinInput) / 100;
            $queryParams['ip-min'] = $ipDecimal;
        }

        try {
            // Ottieni i dati dall'API NASA
            $nasaService = app(NasaApiService::class);
            $data = $nasaService->fetchData('Sentry', $queryParams);

            $asteroids = $data['data'] ?? [];
            $SentryCount = $data['count'] ?? 0;

            // Filtro lato Laravel per date e diametro minimo
            $filteredAsteroids = array_filter($asteroids, function ($asteroid) use ($dateMin, $dateMax, $diamMin) {
                // Filtro per range di anni
                if (!empty($dateMin) || !empty($dateMax)) {
                    $range = $asteroid['range'] ?? '';
                    [$start, $end] = explode('-', $range) + [null, null];

                    if (!$start || !$end) return false;

                    if ($dateMin && $start < substr($dateMin, 0, 4)) {
                        return false;
                    }

                    if ($dateMax && $end > substr($dateMax, 0, 4)) {
                        return false;
                    }
                }

                // Filtro per diametro minimo (convertiamo km -> metri)
                if (!empty($diamMin)) {
                    $diamKm = $asteroid['diameter'] ?? 0;
                    if (($diamKm * 1000) < floatval($diamMin)) {
                        return false;
                    }
                }
                return true;
            });

            // Se non ci sono asteroidi filtrati, mostriamo un errore
            if (empty($filteredAsteroids)) {
                return redirect()->route('sentry.search')->with('error', 'Nessun asteroide trovato che soddisfi i filtri selezionati.');
            }

            $SentryData = [];
            foreach ($filteredAsteroids as $asteroid) {
                $SentryData[] = [
                    'id' => $asteroid['id'] ?? 'N/A',
                    'des' => $asteroid['des'] ?? 'N/A',
                    'fullname' => $asteroid['fullname'] ?? 'N/A',
                    'date' => $asteroid['range'] ?? 'N/A',
                    'diameter' => isset($asteroid['diameter']) ? $asteroid['diameter'] * 1000 : 'N/A', // metri
                    'velocity' => isset($asteroid['v_inf']) ? number_format(floatval($asteroid['v_inf']), 2) : 'N/A',
                    'probability' => isset($asteroid['ip']) ? number_format($asteroid['ip'] * 100, 10) . ' %' : 'N/A',
                    'probability_val' => isset($asteroid['ip']) ? ($asteroid['ip']) : 'N/A',
                    'magnitude' => isset($asteroid['h']) ? number_format($asteroid['h'], 2) : 'N/A',
                    'TS' => isset($asteroid['ts_max']) ? number_format($asteroid['ts_max'], 2) : 'N/A',
                    'PS-max' => isset($asteroid['ps_max']) ? number_format($asteroid['ps_max'], 2) : 'N/A',
                    'PS-cum' => isset($asteroid['ps_cum']) ? number_format($asteroid['ps_cum'], 2) : 'N/A',
                ];
            }

            $SentryCount = count($SentryData);

            return view('tools.searchSentry.search', compact('SentryData', 'SentryCount'));
        } catch (Exception $e) {
            // Gestione degli errori di connessione al server o altri errori
            return redirect()->route('sentry.search')->with('error', 'Errore nel recupero dei dati dall\'API NASA. Riprova più tardi.');
        }
    }

    public function searchByDes(Request $request, $des = null)
    {

        // Recupero utile per predictive Analysis
        if (!$des) {
            $des = $request->input('des');
        }

        if (!$des) {
            return redirect()->back()->with('error', 'Il parametro "des" è richiesto.');
        }

        try {
            $params = ['des' => $des];
            $nasaService = app(NasaApiService::class);

            // Chiamata all’API Sentry
            $sentryResults = $nasaService->fetchData('Sentry', $params);
            $sentrySummary = $sentryResults['summary'] ?? null;
            $sentryData = $sentryResults['data'] ?? [];

            // Controllo se i dati sono vuoti
            if (!$sentrySummary || count($sentryData) === 0) {
                return redirect()->back()->with('error', 'Nessun dato trovato per il corpo "' . $des . '".');
            }

            // Recupero ID da NeoWS
            $neoWsAsteroid = $nasaService->fetchData('NeoWS_ID', [
                'id' => $sentrySummary['des']
            ]);

            // Preparazione dati per grafici
            $chartData = collect($sentryData)->take(50)->map(function ($item) use ($sentrySummary) {
                return formatLineChartPoint($item, $sentrySummary);
            });

            $chartDataExpanded = collect($sentryData)->map(function ($item) use ($sentrySummary) {
                return formatLineChartPoint($item, $sentrySummary);
            });


            // Differenziazione della view in base al path
            $previousUrl = url()->previous();
            if (str_contains($previousUrl, '/predictive-analysis')) {
                return view('tools.predictiveAnalysis.search', compact(
                    'sentryData', 'sentrySummary', 'neoWsAsteroid'
                ));
            }

            // Default: view classica
            return view('tools.searchSentry.singleResult', compact(
                'sentryData', 'sentrySummary', 'neoWsAsteroid', 'chartData', 'chartDataExpanded'
            ));

        } catch (Exception $e) {
            // Log errore per debugging
            return redirect()->back()->with('error', 'Si è verificato un errore durante la ricerca: ' . $e->getMessage());
        }
    }


    public function getPredictiveAnalysesView()
    {
        return view('tools.predictiveAnalysis.search');
    }


    public function runPrediction(Request $request)
    {
        $energy = $request->input('energy');
        $velocity = $request->input('velocity');

        if (!$energy || !$velocity) {
            return back()->with('error', 'Dati mancanti dal form.');
        }

        $pythonScript = base_path('python/Asteroid_Impact_Prediction/predict.py');
        $pythonPath = base_path('python/Asteroid_Impact_Prediction/myvenv/Scripts/python.exe');

        $process = new Process([$pythonPath, $pythonScript]);
        $process->setInput(json_encode([
            'energy' => $energy,
            'velocity' => $velocity
        ]));

        $process->run();

        if (!$process->isSuccessful()) {
            return back()->with('error', 'Errore script: ' . $process->getErrorOutput());
        }

        $output = json_decode($process->getOutput(), true);

        if (!$output || isset($output['error'])) {
            return back()->with('error', $output['error'] ?? 'Output nullo dallo script.');
        }

        $result = $output['energy'];

        return view('tools.predictiveAnalysis.search', compact('result'));
    }

}


function formatLineChartPoint($item, $sentrySummary)
{
    $dateParts = explode('.', $item['date']);
    $dateString = $dateParts[0];
    $decimalPart = isset($dateParts[1]) ? ('0.' . $dateParts[1]) : '0';

    $totalMinutes = (float)$decimalPart * 24 * 60;
    $hours = floor($totalMinutes / 60);
    $minutes = floor($totalMinutes % 60);
    $formattedTime = sprintf('%02d:%02d', $hours, $minutes);

    $method = $sentrySummary['method'] ?? null;

    $full = [
        'ip' => $item['ip'] ?? null,
        'date' => $item['date'] ?? null,
        'ps' => $item['ps'] ?? null,
        'ts' => $item['ts'] ?? null,
    ];

    if ($method === 'IOBS' && isset($item['sigma_vi'])) {
        $full['sigma_vi'] = $item['sigma_vi'];
    }

    if ($method === 'LOV') {
        $lovFields = ['dist', 'width', 'sigma_imp', 'sigma_lov', 'stretch'];
        foreach ($lovFields as $field) {
            if (isset($item[$field])) {
                $full[$field] = $item[$field];
            }
        }
    }

    if ($method === 'MC' && isset($item['sigma_mc'])) {
        $full['sigma_mc'] = $item['sigma_mc'];
    }

    return [
        'x' => $dateString,
        'y' => (float)($item['ip'] ?? 0),
        'tooltipDate' => $dateString . ' ' . $formattedTime,
        'full' => $full,
    ];

}
