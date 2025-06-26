<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\NasaApiService;
use Illuminate\Http\Request;

/**
 * V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
 * Copyright (c) 2025 Manuel Carlucci
 *
 * This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/4.0/
 */

class FireballController extends Controller
{

    /**
     * Cerca eventi fireball ed applica i filtri
     *
     * @return \Illuminate\View\View
     */
    public function searchFireballElements(Request $request)
    {
        // Parametri base per la chiamata API
        $params = [
            'sort' => '-date', // Ordina per data decrescente
            'vel-comp' => '1', // Parametro di velocità
        ];

        // Aggiungi i filtri dinamici se sono stati inviati nella richiesta
        if ($request->has('date-min')) {
            $params['date-min'] = $request->input('date-min');
        }
        if ($request->has('date-max')) {
            $params['date-max'] = $request->input('date-max');
        }
        if ($request->has('alt-min')) {
            $params['alt-min'] = $request->input('alt-min');
        }
        if ($request->has('alt-max')) {
            $params['alt-max'] = $request->input('alt-max');
        }
        if ($request->has('energy-min')) {
            $params['energy-min'] = $request->input('energy-min');
        }
        if ($request->has('energy-max')) {
            $params['energy-max'] = $request->input('energy-max');
        }
        if ($request->has('impact-e-min')) {
            $params['impact-e-min'] = $request->input('impact-e-min');
        }
        if ($request->has('impact-e-max')) {
            $params['impact-e-max'] = $request->input('impact-e-max');
        }

        // Chiamata al servizio NASA per ottenere i dati
        $nasaService = app(NasaApiService::class);
        $fireballData = $nasaService->fetchData('Fireball', $params);

        // Controlla se c'è stato un errore nella risposta
        if (isset($fireballData['code']) && $fireballData['code'] == '500') {
            return back()->with('error', 'Internal Server Error - il database non è disponibile al momento della richiesta.');
        }

        $rawResults = $fireballData['data'] ?? [];

        $results = [];
        $energyByYear = [];

        // Elaborazione dei dati ottenuti
        foreach ($rawResults as $item) {
            $date = $item[0] ?? null;
            $energy = $item[1] ?? null;
            $impact = $item[2] ?? null;
            $lat = $item[3] ?? null;
            $latDir = $item[4] ?? '';
            $lon = $item[5] ?? null;
            $lonDir = $item[6] ?? '';
            $altitude = $item[7] ?? null;

            // Calcolo della velocità
            $vx = isset($item[9]) ? (float)$item[9] : null;
            $vy = isset($item[10]) ? (float)$item[10] : null;
            $vz = isset($item[11]) ? (float)$item[11] : null;

            $speed = ($vx !== null && $vy !== null && $vz !== null)
                ? sqrt($vx ** 2 + $vy ** 2 + $vz ** 2)
                : null;

            $results[] = [
                'date' => $date,
                'coordinate' => ($lat !== null && $lon !== null) ? "{$lat} {$latDir} - {$lon} {$lonDir}" : 'N/A',
                'altitude' => $altitude !== null ? number_format($altitude, 2) : 'N/A',
                'speed' => $speed !== null ? number_format($speed, 2) : 'N/A',
                'speed_components' => ($vx !== null && $vy !== null && $vz !== null)
                    ? "Vx: {$vx} km/s\nVy: {$vy} km/s\nVz: {$vz} km/s"
                    : '',
                'energy' => $energy !== null ? number_format($energy, 2) . ' ×10¹⁰ J' : 'N/A',
                'impact' => $impact !== null ? number_format($impact, 2) . ' kT' : 'N/A',
            ];

            // Elaborazione per il grafico
            if ($date && $impact) {
                $year = substr($date, 0, 4); // Estrai l'anno dalla data

                if (!isset($energyByYear[$year])) {
                    $energyByYear[$year] = [
                        'max' => $impact,
                        'min' => $impact,
                        'sum' => $impact,
                        'count' => 1,
                    ];
                } else {
                    $energyByYear[$year]['max'] = max($energyByYear[$year]['max'], $impact);
                    $energyByYear[$year]['min'] = min($energyByYear[$year]['min'], $impact);
                    $energyByYear[$year]['sum'] += $impact;
                    $energyByYear[$year]['count']++;
                }
            }
        }

        // Calcola la media per ogni anno
        foreach ($energyByYear as $year => $data) {
            $energyByYear[$year]['avg'] = $data['sum'] / $data['count'];
        }

        // Ordina i dati per anno
        ksort($energyByYear);

        // Ottieni il numero totale di fireballs
        $fireballCount = isset($fireballData['count']) && is_numeric($fireballData['count'])
            ? (int)$fireballData['count']
            : 0;

        if ($fireballCount === 0) {
            return back()->with('error', 'Nessun evento di fireball trovato con i criteri selezionati.');
        }

        // Passa i dati alla vista
        return view('tools.searchFireball.search', [
            'fireballs' => $results,
            'fireballCount' => $fireballCount,
            'filtered' => $request->has('date-min') || $request->has('date-max') || $request->has('alt-min') || $request->has('alt-max') || $request->has('energy-min') || $request->has('energy-max') || $request->has('impact-e-min') || $request->has('impact-e-max'),
            'energyByYear' => $energyByYear,
            'filters' => $params,
        ]);
    }
}
