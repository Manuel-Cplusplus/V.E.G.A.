<?php

namespace App\Http\Controllers;

use App\Models\Asteroid;
use App\Services\NasaApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
 * Copyright (c) 2025 Manuel Carlucci
 *
 * This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/4.0/
 */

class AsteroidController extends Controller
{

    public function index()
    {
        // Ottenere la data di oggi
        $today = now()->format('Y-m-d');

        // Chiamata al servizio NasaApi
        $nasaService = app(NasaApiService::class);
        $data = $nasaService->fetchData('NeoWS', [
            'start_date' => $today,
            'end_date' => $today
        ]);


        $asteroids = [];
        $AsteroidCount = 0;
        $AsteroidVisualizationData = [];

        if ($data && !empty($data['near_earth_objects'][$today])) {
            $AsteroidCount = $data['element_count'];

            foreach ($data['near_earth_objects'][$today] as $asteroid) {

                $asteroids[] = [
                    'id' => $asteroid['id'],
                    'name' => $asteroid['name'],
                    'diameter' => round(($asteroid['estimated_diameter']['meters']['estimated_diameter_min'] +
                            $asteroid['estimated_diameter']['meters']['estimated_diameter_max']) / 2, 2),
                    'miss_distance' => round($asteroid['close_approach_data'][0]['miss_distance']['kilometers'], 2),
                    'miss_distance_lunar' => round($asteroid['close_approach_data'][0]['miss_distance']['lunar'], 2),
                    'velocity' => round($asteroid['close_approach_data'][0]['relative_velocity']['kilometers_per_second'], 2),
                    'hazardous' => $asteroid['is_potentially_hazardous_asteroid'],
                    'is_sentry_object' => $asteroid['is_sentry_object'],
                    'impact_time' => isset($asteroid['close_approach_data'][0]['epoch_date_close_approach'])
                        ? $asteroid['close_approach_data'][0]['epoch_date_close_approach'] / 1000  // Converti millisecondi in secondi
                        : null,
                ];

            }
        }


        // Ordina gli asteroidi: prima quelli con impatto più vicino, poi quelli già passati
        $current_time = now()->timestamp;
        usort($asteroids, function ($a, $b) use ($current_time) {
            if ($a['impact_time'] < $current_time && $b['impact_time'] >= $current_time) {
                return 1;
            } elseif ($b['impact_time'] < $current_time && $a['impact_time'] >= $current_time) {
                return -1;
            }
            return $a['impact_time'] <=> $b['impact_time'];
        });

        //dd($asteroids);
        return view('homepage', compact('asteroids', 'AsteroidCount'));
    }


    /**
     * Mostra la vista di ricerca di asteroidi utilizzando le API di NeoWS e CAD.
     *
     * @return \Illuminate\View\View
     */
    public function getSearchView()
    {
        return view('tools.searchAsteroid.search');
    }


    public function searchAsteroid(Request $request)
    {
        $filters = array_filter($request->except(['_token', 'search', 'search_query', 'unit_min', 'unit_max']), function ($value) {
            return !is_null($value) && $value !== '';
        });

        $searchQuery = $request->input('search_query') ?? $request->input('search');

        if (!empty($filters)) {
            return $this->searchByFilters($request);
        }

        if (!empty($searchQuery)) {
            // Reindirizza alla rotta che mostra il dettaglio asteroide
            return redirect()->route('asteroid.show', ['id' => $searchQuery]);
            //return $this->searchByID($request);   -> eliminato per problema con redirect->back() nell'aggiunta/rimozione preferiti
        }

        return redirect()->back()->with('error', 'Inserisci almeno un criterio di ricerca.');

    }

    public function searchByID(Request $request, $id = null)
    {

        /*** Chiamate API **/
        $searchQuery = $id ?? $request->input('search_query') ?? $request->input('search') ?? $request->input('des');
        // $searchQuery = $id per rotta asteroid.show
        // $request->input('des'); per AsteroidComparison


        // Chiamata al servizio NasaApiService per NeoWs
        $nasaService = app(\App\Services\NasaApiService::class);
        $neoWsAsteroid = $nasaService->fetchData('NeoWS_ID', [
            'id' => $searchQuery
        ]);

        // Verifica che NeoWs abbia restituito dati validi
        if (!$neoWsAsteroid || !isset($neoWsAsteroid['designation'])) {
            return redirect()->back()->with('error', 'Asteroide non trovato o errore nella richiesta API.');
        }


        // Usa la designation per il CAD
        $designation = $neoWsAsteroid['designation'];

        // Chiamata al servizio NasaApiService per CAD (senza API key)
        $cadData = $nasaService->fetchData('CAD', [
            'des' => $designation,
            'date-min' => '1500-01-01',
            'date-max' => '2900-01-01',
            'dist-max' => '10000000000000',
            'diameter' => '1',
            'sort' => 'dist',
        ]);
        //dd($cadData);

        if (!$neoWsAsteroid) {
            return redirect()->back()->with('error', 'Asteroide non trovato o errore nella richiesta API.');
        }

        /*** Controllo Dati da Asteroid NeoWS ***/
        $closeApproachExists = !empty($neoWsAsteroid['close_approach_data']);

        // Se disponibili in NeoWS
        $missDistanceKm = $closeApproachExists && isset($neoWsAsteroid['close_approach_data'][0]['miss_distance']['kilometers'])
            ? number_format($neoWsAsteroid['close_approach_data'][0]['miss_distance']['kilometers'], 2)
            : null;

        $velocityKmS = $closeApproachExists && isset($neoWsAsteroid['close_approach_data'][0]['relative_velocity']['kilometers_per_second'])
            ? number_format($neoWsAsteroid['close_approach_data'][0]['relative_velocity']['kilometers_per_second'], 2)
            : null;

        $approachDate = $closeApproachExists && isset($neoWsAsteroid['close_approach_data'][0]['close_approach_date'])
            ? $neoWsAsteroid['close_approach_data'][0]['close_approach_date']
            : null;

        // Se mancanti, estrai dal CAD
        if ((!$missDistanceKm || !$velocityKmS || !$approachDate) && isset($cadData['data'][0])) {
            $cadEntry = $cadData['data'][0]; // Primo avvicinamento ordinato per distanza
            $missDistanceKm ??= number_format($cadEntry[4] * 149597870.7, 2); // AU → km
            $velocityKmS ??= number_format($cadEntry[7], 2);
            $approachDate ??= $cadEntry[3]; // Es: "2174-Jul-28 02:52"
        }


        // Controlla se CAD ha il diametro disponibile
        $diameter = null;
        if ($cadData && isset($cadData['data'][0][11])) {
            $diameter = $cadData['data'][0][11] * 1000;
        } else {
            // Se CAD non ha il diametro, calcola la media da NeoWS
            $diameter = round(
                ($neoWsAsteroid['estimated_diameter']['meters']['estimated_diameter_min'] +
                    $neoWsAsteroid['estimated_diameter']['meters']['estimated_diameter_max']) / 2
            );
        }

        /*** Estrapolazione Dati Base **/
        $closeApproachData = $neoWsAsteroid['close_approach_data'] ?? [];

        $asteroidData = [
            'id' => $neoWsAsteroid['id'],
            'name' => $neoWsAsteroid['name'],
            'diameter_max' => number_format($neoWsAsteroid['estimated_diameter']['meters']['estimated_diameter_max'], 2),
            'diameter_min' => number_format($neoWsAsteroid['estimated_diameter']['meters']['estimated_diameter_min'], 2),
            'diameter' => $diameter,
            'diameter_uncertainty' => $cadData && isset($cadData['data'][0][12]) ? number_format($cadData['data'][0][12] * 1000, 2) : null,
            'miss_distance_km' => $missDistanceKm,
            'miss_distance_lunar' => !empty($closeApproachData) && isset($closeApproachData[0]['miss_distance']['lunar'])
                ? round($closeApproachData[0]['miss_distance']['lunar'], 2)
                : null,
            'velocity_km_s' => $velocityKmS,
            'close_approach_date' => $approachDate,
            'orbiting_body' => !empty($closeApproachData) && isset($closeApproachData[0]['orbiting_body'])
                ? $this->getOrbitingBodyName($closeApproachData[0]['orbiting_body'])
                : 'N/A',
            'is_hazardous' => $neoWsAsteroid['is_potentially_hazardous_asteroid'],
            'is_sentry_object' => $neoWsAsteroid['is_sentry_object'],
            'link' => $neoWsAsteroid['nasa_jpl_url'] . '&view=VOP',
            'magnitude' => $neoWsAsteroid['absolute_magnitude_h'],
        ];


        /** Prendi dati tecnici da AsteroidNeoWS **/
        $technicalData = $neoWsAsteroid['orbital_data'] ?? [];
        $orbitClass = $technicalData['orbit_class'] ?? [];


        /*** Close Approach Data **/

        // Prepara la collezione dei dati CAD normalizzati
        $cadCollection = collect($cadData['data'] ?? [])->map(function ($entry) {
            return [
                'close_approach_date' => \Carbon\Carbon::parse($entry[3])->toDateString(),
                'miss_distance_km' => number_format($entry[4] * 149597870.7, 2), // AU to KM
                'relative_velocity_km_s' => number_format($entry[7], 2),
            ];
        });

        // Raggruppa i dati di Close Approach (se presenti)
        $closeApproachData = collect($neoWsAsteroid['close_approach_data'] ?? [])->map(function($approach) {
            return [
                'close_approach_date' => $approach['close_approach_date'] ?? null,
                'relative_velocity_km_s' => isset($approach['relative_velocity']['kilometers_per_second'])
                    ? number_format($approach['relative_velocity']['kilometers_per_second'], 2)
                    : null,
                'miss_distance_ld' => $approach['miss_distance']['lunar'] ?? null,
                'miss_distance_au' => $approach['miss_distance']['astronomical'] ?? null,
                'miss_distance_km' => $approach['miss_distance']['kilometers'] ?? null,
                'orbiting_body' => isset($approach['orbiting_body'])
                    ? $this->getOrbitingBodyName($approach['orbiting_body'])
                    : null,
            ];
        });

        /** Estrazione delle date e le distanze di avvicinamento per il grafico **/
        $dates = $closeApproachData->pluck('close_approach_date')->toArray();
        $missDistances = $closeApproachData->pluck('miss_distance_km')->toArray();


        // Se i dati di NeoWS sono vuoti, prendi i dati da CAD
        if ($closeApproachData->isEmpty() && $cadCollection->isNotEmpty()) {
            $closeApproachData = $cadCollection->map(function ($cadEntry) {

                // Rimuovi la virgola e converti la stringa in un numero float
                $missDistanceKm = floatval(str_replace(',', '', $cadEntry['miss_distance_km']));

                // Conversione dei dati da CAD in AU e LD
                $missDistanceAu = $missDistanceKm / 149597870.7;
                $missDistanceLd = $missDistanceKm / 384400;

                return [
                    'close_approach_date' => $cadEntry['close_approach_date'],
                    'relative_velocity_km_s' => $cadEntry['relative_velocity_km_s'],
                    'miss_distance_ld' => $missDistanceLd,
                    'miss_distance_au' => $missDistanceAu,
                    'miss_distance_km' => $missDistanceKm,
                    'orbiting_body' => null, // Non disponibile da CAD
                ];
            });


            // Se i dati di NeoWS non sono disponibili, inverti l'ordine
            // Siccome CAD, tramite sort=dist, ottiene i valori in ordine invertito rispetto neoWS
            // sort = dist risulta essenziale per stampare le informazioni basilari, ma in questo caso dobbiamo invertire l'ordine
            // SI noti che si da la priorità ai dati di NeoWS in quanto più precisi (numero di dati maggiori), ma se questi non sono presenti vengono restituiti lo stesso alcuni dati
            /** Estrazione delle date e le distanze di avvicinamento per il grafico **/
            $dates = $closeApproachData->pluck('close_approach_date')->toArray();
            $missDistances = $closeApproachData->pluck('miss_distance_km')->toArray();
            // Inverte l'ordine delle date e delle distanze
            $dates = array_reverse($dates);
            $missDistances = array_reverse($missDistances);
        }


        /** Incontri ravvicinati solo per la Terra **/
        $earthApproachData = collect();
        $earthDates = [];
        $earthMissDistances = [];

        // Verifica che ci siano dati di avvicinamento e che abbiano il campo orbiting_body
        if ($closeApproachData->isNotEmpty() && $closeApproachData->first() && isset($closeApproachData->first()['orbiting_body'])) {
            $earthApproachData = $closeApproachData->filter(function($approach) {
                return $approach['orbiting_body'] === 'Terra';
            })->values();

            // Estrai date e distanze per gli incontri con la Terra
            $earthDates = $earthApproachData->pluck('close_approach_date')->toArray();
            $earthMissDistances = $earthApproachData->pluck('miss_distance_km')->toArray();
        }

        $AsteroidCount = 1;


        /** Differenziazione della view in base al path **/
        $previousUrl = url()->previous();
        if (str_contains($previousUrl, '/compare-asteroids')) {
            $params = ['des' => $searchQuery];
            $sentryResults = $nasaService->fetchData('Sentry', $params);
            $sentrySummary = $sentryResults['summary'] ?? null;

            return view('tools.asteroidComparison.search', compact('asteroidData', 'sentrySummary', 'closeApproachData', 'dates', 'missDistances', 'earthDates', 'earthMissDistances'));
        }

        return view('tools.searchAsteroid.results', compact('asteroidData', 'AsteroidCount', 'technicalData', 'orbitClass', 'closeApproachData', 'dates', 'missDistances', 'earthDates', 'earthMissDistances'));
    }



    public function searchByFilters(Request $request)
    {
        /** Validazione form **/
        $validated = $request->validate([
            'search_query' => 'nullable|string|max:255',
            'date_filter' => 'nullable|in:specific,range',
            'specific_date' => 'nullable|date',
            'date-min' => 'nullable|date',
            'date-max' => 'nullable|date|after_or_equal:date-min',
            'dist-min' => 'nullable|numeric|min:0',
            'unit_min' => 'nullable|in:km,LD,UA',
            'dist-max' => 'nullable|numeric|min:0',
            'unit_max' => 'nullable|in:km,LD,UA',
            'pha' => 'nullable|in:on',
            'h-min' => 'nullable|numeric|min:0',
            'h-max' => 'nullable|numeric|min:0|after_or_equal:h-min',
            'v-rel-min' => 'nullable|numeric|min:0',
            'v-rel-max' => 'nullable|numeric|min:0|after_or_equal:v-rel-min',
            'class' => 'nullable|string',
            'body' => 'nullable|string',
        ]);

        /** Gestione Filtri **/
        $dateFilter = $validated['date_filter'] ?? null;
        if ($dateFilter == 'specific') {
            // Imposta date-min alla data specifica
            $validated['date-min'] = $validated['specific_date'];

            // Aggiungi un giorno a specific_date per date-max
            $validated['date-max'] = \Carbon\Carbon::parse($validated['specific_date'])->addDay()->toDateString();
        }

        $validated['dist-max'] = ($validated['dist-max'] ?? null) && ($validated['unit_max'] ?? null)
            ? $validated['dist-max'] . $validated['unit_max']
            : null;

        $validated['dist-min'] = ($validated['dist-min'] ?? null) && ($validated['unit_min'] ?? null)
            ? $validated['dist-min'] . $validated['unit_min']
            : null;

        $validated['pha'] = $validated['pha'] ?? false; // Se non c'è 'pha', lo imposta a false
        if ($validated['pha'] != null) {
            $validated['pha'] = true;
        } else {
            $validated['pha'] = false;
        }



        /** Recupero des ed altri dati da Asteroid NeoWS **/ /* Se Inserito ID in barra ricerca */
        $searchQuery = $request->input('search_query') ?? $request->input('search');
        $nasaService = app(\App\Services\NasaApiService::class);
        $neoWsAsteroid = $nasaService->fetchData('NeoWS_ID', [
            'id' => $searchQuery
        ]);
        $designation = $neoWsAsteroid['designation'] ?? null;

        /** Filtri per URL CAD **/
        $filters = array_filter([
            'des' => $designation ?? null,
            'date-min' => $validated['date-min'] ?? null,
            'date-max' => $validated['date-max'] ?? null,
            'dist-min' => $validated['dist-min'],
            'dist-max' => $validated['dist-max'],
            'pha' => $validated['pha'],
            'h-min' => $validated['h-min'] ?? null,
            'h-max' => $validated['h-max'] ?? null,
            'v-rel-min' => $validated['v-rel-min'] ?? null,
            'v-rel-max' => $validated['v-rel-max'] ?? null,
            'class' => $validated['class'] ?? null,
            'body' => $validated['body'] ?? null,
            'diameter' => 1, // ritorniamo i dati sul diametro
            'limit' => 50,  // limitiamo a 50 risultati
            //'sort' => 'dist',
            'fullname' => 1,
        ]);

        // Rimuove eventuali chiavi con valore null
        $filters = array_filter($filters, fn($value) => !is_null($value));

        $nasaService = new NasaApiService();
        $data = $nasaService->fetchData('CAD', $filters);
        $AsteroidData = $data['data'] ?? [];

        //dd($AsteroidData);
        /** Recupera dati da chiamate HTTP **/
        $formattedAsteroids = array_map(function($asteroid) use ($nasaService, $neoWsAsteroid, $validated) {
            // Se search_query è vuoto, chiama NeoWS per ogni asteroide per ottenere i dettagli
            // Commentato per evitare chiamate multiple
           /* if (!$neoWsAsteroid) {
                $neoWsAsteroid = $nasaService->fetchData('NeoWS_ID', [
                    'id' => $asteroid[0]
                ]);
            }*/


            // Validazione dati neoWS
            $velocity_km_s = isset($neoWsAsteroid['close_approach_data'][0]['relative_velocity']['kilometers_per_second'])
                ? number_format($neoWsAsteroid['close_approach_data'][0]['relative_velocity']['kilometers_per_second'], 2)
                : null;
            /*$is_hazardous = $neoWsAsteroid['is_potentially_hazardous_asteroid'] ?? null;
            $is_sentry_object = $neoWsAsteroid['is_sentry_object'] ?? null;*/

            // Recupero Diametro
            $diameter = $asteroid[12] ?? null;
            if (is_null($diameter)) {
                // Verifica se l'array 'estimated_diameter' esiste e contiene i dati necessari
                if (isset($neoWsAsteroid['estimated_diameter']['meters']['estimated_diameter_min']) &&
                    isset($neoWsAsteroid['estimated_diameter']['meters']['estimated_diameter_max'])) {

                    // Calcola il diametro medio se i dati sono presenti
                    $diameter = number_format(round(
                        ($neoWsAsteroid['estimated_diameter']['meters']['estimated_diameter_min'] +
                            $neoWsAsteroid['estimated_diameter']['meters']['estimated_diameter_max']) / 2
                    ), 2);
                } else {
                    // Se non ci sono dati sul diametro, imposta il diametro a null
                    $diameter = null;
                }
            }

            // Converte la distanza in base all'unità di misura selezionata nel filtro
            $distanceInUA = $asteroid[4]; // La distanza è in UA
            $unitToUse = 'km'; // Default a km
            if ($validated['dist-min'] !== null) {
                $unitToUse = $validated['unit_min'] ?? 'km'; // Se dist-min è presente, usa unit-min
            } elseif ($validated['dist-max'] !== null) {
                $unitToUse = $validated['unit_max'] ?? 'km'; // Se dist-max è presente, usa unit-max
            }
            $distance = $this->convertDistance($distanceInUA, $unitToUse);

            /** Recupero ID & Des dal fullname **/
            $fullname = trim($asteroid[13]);

            $id = null;
            $designation = null;

            if (preg_match('/^([0-9]+)\s*\(([^)]+)\)$/', trim($fullname), $matches)) {
                // Caso: 214869 (2007 PA8)
                $id = $matches[1];
                $designation = $matches[2];
            } elseif (preg_match('/^\(([^)]+)\)$/', trim($fullname), $matches)) {
                // Caso: (2007 PA8)
                $designation = $matches[1];
            } elseif (is_numeric(trim($fullname))) {
                // Caso: 214869
                $id = trim($fullname);
            }


            //dd($asteroidId, $designation);

            // Valori restituibili alla vista
            return [
                'fullname' => $asteroid[13],
                'id' => $id,
                'designation' => $designation,
                'diameter' => number_format($diameter * 1000,2),
                'date' => $asteroid[3],
                'distance' => number_format($distance,2), // La distanza convertita
                'miss_distance_lunar' => number_format($distance / 384400, 2), // Conversione da km a distanze lunari
                'velocity_rel_km_s' => number_format($velocity_km_s, 2) ?? number_format($asteroid[7],2),
                'velocity_abs_km_s' => number_format($asteroid[8],2),
                'magnitude' => number_format($asteroid[10],2),
                'unit' => $unitToUse,
            ];
        }, $AsteroidData);

        $AsteroidCount = count($formattedAsteroids);
        if ($AsteroidCount === 0) {
            return redirect()->back()->with('warning', 'Nessun asteroide trovato per i filtri selezionati. Prova a modificare i filtri o aspetti che si aggiorni il limite di richieste API disponibile.');
        }
        // Se il risultato è un unico asteroide allora vedi i dettagli di quell'asteroide
        else if($AsteroidCount == 1) {
            return redirect()->route('asteroid.show', ['id' => $formattedAsteroids[0]['des']]);
        }

        return view('tools.searchAsteroid.results', compact('formattedAsteroids', 'AsteroidCount','filters'));
    }

    // Metodo helper per ottenere il nome del corpo orbitante
    private function getOrbitingBodyName($bodyCode)
    {
        $bodies = [
            'Merc' => 'Mercurio',
            'Venus' => 'Venere',
            'Earth' => 'Terra',
            'Mars' => 'Marte',
            'Juptr' => 'Giove',
            'Satrn' => 'Saturno',
            'Urnus' => 'Urano',
            'Neptn' => 'Nettuno',
            'Pluto' => 'Plutone',
            'Moon' => 'Luna',
        ];

        return $bodies[$bodyCode] ?? $bodyCode;
    }


    // Funzione di conversione per la distanza
    function convertDistance($distance, $unit) {
        switch ($unit) {
            case 'km':
                return $distance * 149597870.7; // Converte UA in km
            case 'LD':
                return $distance * 384.4; // Converte UA in LD
            case 'UA':
            default:
                return $distance; // Lascia la distanza in UA
        }
    }



    public function getCloseApproachesView()
    {
        return view('tools.searchCloseApproaches.search');
    }

    public function searchByDate(Request $request, NasaApiService $nasaApiService)
    {
        // Validazione dei dati
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        // Costruzione parametri per NeoWS (max 7 giorni, controllato lato frontend)
        $params = [
            'start_date' => $startDate,
            'end_date'   => $endDate,
        ];

        // Richiesta tramite NasaApiService
        $neoData = $nasaApiService->fetchData('NeoWS', $params);

        if (!$neoData || !isset($neoData['near_earth_objects'])) {
            return redirect()->back()->with('error', 'Errore nel recupero dei dati dall\'API NASA. Riprova più tardi o cambia parametri.');
        }

        $AsteroidCount = $neoData['element_count'] ?? 0;
        $formattedAsteroids = [];

        foreach ($neoData['near_earth_objects'] as $date => $asteroids) {
            foreach ($asteroids as $asteroid) {
                $formattedAsteroids[] = [
                    'id' => $asteroid['id'],
                    'designation' => $asteroid['name'],
                    'diameter' => number_format((round($asteroid['estimated_diameter']['meters']['estimated_diameter_max'], 2) +
                            round($asteroid['estimated_diameter']['meters']['estimated_diameter_min'], 2)) / 2, 2),
                    'velocity' => number_format(round($asteroid['close_approach_data'][0]['relative_velocity']['kilometers_per_second'], 2)),
                    'distance' => number_format(round($asteroid['close_approach_data'][0]['miss_distance']['kilometers'], 2)),
                    'data' => $asteroid['close_approach_data'][0]['close_approach_date_full'],
                    'body' => $this->getOrbitingBodyName($asteroid['close_approach_data'][0]['orbiting_body']),
                    'hazardous' => $asteroid['is_potentially_hazardous_asteroid'],
                    'is_sentry_object' => $asteroid['is_sentry_object'],
                ];
            }
        }

        //dd($formattedAsteroids);

        $hazardousCount = collect($formattedAsteroids)->where('hazardous', true)->count();
        $nonHazardousCount = collect($formattedAsteroids)->where('hazardous', false)->count();

        $sentryCount = collect($formattedAsteroids)->where('is_sentry_object', true)->count();
        $nonSentryCount = collect($formattedAsteroids)->where('is_sentry_object', false)->count();


        if($AsteroidCount === 0) {
            return redirect()->back()->with('warning', 'Nessun asteroide trovato per i filtri selezionati. Prova a modificare i filtri o aspetti che si aggiorni il limite di richieste API disponibile.');
        } elseif ($AsteroidCount == 1) {
            return redirect()->route('asteroid.show', ['id' => $formattedAsteroids[0]['id']]);
        } else {
            return view('tools.searchCloseApproaches.results', [
                'formattedAsteroids' => $formattedAsteroids,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'AsteroidCount' => $AsteroidCount,
                'hazardousCount' => $hazardousCount,
                'nonHazardousCount' => $nonHazardousCount,
                'sentryCount' => $sentryCount,
                'nonSentryCount' => $nonSentryCount,
            ]);
        }

    }

    // Confronto Asteroidi
    public function getCompareAsteroidsView()
    {
        return view('tools.asteroidComparison.search');
    }

}
