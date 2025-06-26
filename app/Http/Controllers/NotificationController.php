<?php

    namespace App\Http\Controllers;

    use App\Http\Controllers\Controller;
    use App\Models\FavoriteAsteroid;
    use App\Notifications\FavoriteAsteroidUpdated;
    use App\Services\NasaApiService;
    use Illuminate\Http\Request;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\Auth;

    /**
     * V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
     * Copyright (c) 2025 Manuel Carlucci
     *
     * This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
     * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/4.0/
     */

    class NotificationController extends Controller
    {
        public function checkFavoriteAsteroids($user)
        {
            $user = Auth::user();
            $nasaService = app(NasaApiService::class);

            // Controlla se l'utente ha asteroidi preferiti
            if ($user->favoriteAsteroids->isEmpty()) {
                return response()->json(['status' => 'nessun asteroide preferito']);
            }

            foreach ($user->favoriteAsteroids as $asteroid) {
                //dump($asteroid);


                $changes = [];
                $NewCadDate = null;
                $NewIsSentry = false;
                $NewImpactProbability = null;
                $NewImpactDate = null;
                $NewTorinoScale = null;

                // Recupera i dati da NeoWS
                $neoWsAsteroid = $nasaService->fetchData('NeoWS_ID', ['id' => $asteroid->asteroid_id]);

                // Recupera CAD
                if (!empty($neoWsAsteroid['close_approach_data'])) {
                    $today = Carbon::today();

                    $closestFutureCad = collect($neoWsAsteroid['close_approach_data'])
                        ->filter(function ($approach) use ($today) {
                            return isset($approach['close_approach_date']) &&
                                Carbon::parse($approach['close_approach_date'])->greaterThanOrEqualTo($today);
                        })
                        ->sortBy('close_approach_date')
                        ->first();

                    $NewCadDate = $closestFutureCad['close_approach_date'] ?? null;

                    if ($NewCadDate && $asteroid->cad !== $NewCadDate && $NewCadDate!==null) {
                        if ($asteroid->cad === null) {
                            $changes[] = "Data di Incontro Ravvicinato aggiornata a {$NewCadDate}";
                        } else {
                            $changes[] = "Data di Incontro Ravvicinato aggiornata da {$asteroid->cad} a {$NewCadDate}";
                        }
                    }
                }

                // Determina se è un oggetto monitorato da Sentry
                $NewIsSentry = isset($neoWsAsteroid['is_sentry_object']) ? (int)$neoWsAsteroid['is_sentry_object'] : null;

                if($NewIsSentry === null) {
                    continue;
                } elseif (!$NewIsSentry) {
                    if ($NewIsSentry !== $asteroid->isSentry) {
                        $changes[] = "Questo asteroide non impatterà più sulla Terra.";
                    }
                } else {
                    // Se è un oggetto Sentry, recupera i dati specifici
                    $sentryResults = $nasaService->fetchData('Sentry', ['des' => $asteroid->asteroid_id]);

                    // recupera le informazioni di impatto della data più vicina alla data odierna
                    if (isset($sentryResults['data']) && is_array($sentryResults['data'])) {
                        $now = Carbon::now();

                        $latest = collect($sentryResults['data'])
                            ->filter(function ($item) use ($now) {
                                // Pulisce la data rimuovendo la parte decimale e la confronta
                                $dateOnly = explode('.', $item['date'])[0];
                                return Carbon::parse($dateOnly)->greaterThan($now);
                            })
                            ->sortBy(function ($item) {
                                return Carbon::parse(explode('.', $item['date'])[0]);
                            })
                            ->first();

                        if ($latest) {
                            $NewImpactProbability = isset($latest['ip']) ? (float)$latest['ip'] : null;

                            $NewImpactDate = \Carbon\Carbon::parse(explode('.', $latest['date'])[0])->toDateString();

                            $NewTorinoScale = isset($latest['ts']) ? (int)$latest['ts'] : null;
                        }
                    }

                    if ($asteroid->isSentry !== $NewIsSentry) {
                        $changes[] = "Questo Asteroide è passato dal non colpire la Terra ad avere possibili impatti futuri.";
                    } else {
                        if ($NewImpactDate && $asteroid->impact_date !== $NewImpactDate) {
                            $changes[] = "Data di possibile impatto cambiata: da {$asteroid->impact_date} a {$NewImpactDate}";
                        }
                        if ($NewImpactProbability !== null && abs($asteroid->impact_probability - $NewImpactProbability) > 1e-11) {
                            $changes[] = "Probabilità di possibile impatto cambiata: da {$asteroid->impact_probability} a {$NewImpactProbability}";
                        }
                        if ($asteroid->torino_scale !== $NewTorinoScale) {
                            $changes[] = "Pericolosità di impatto cambiata: da {$asteroid->torino_scale} a {$NewTorinoScale}";
                        }
                    }
                }
                //dump($NewCadDate, $NewIsSentry, $NewImpactProbability, $NewImpactDate, $NewTorinoScale);


                // Invia una notifica se ci sono cambiamenti
                if (!empty($changes)) {
                    // Costruisci solo i campi non nulli da aggiornare nel DB
                    $updateData = [];

                    if ($NewCadDate !== null) {
                        $updateData['cad'] = $NewCadDate;
                    }
                    if ($NewIsSentry !== null) {
                        $updateData['isSentry'] = $NewIsSentry;
                    }
                    if ($NewImpactProbability !== null) {
                        $updateData['impact_probability'] = $NewImpactProbability;
                    }
                    if ($NewImpactDate !== null) {
                        $updateData['impact_date'] = $NewImpactDate;
                    }
                    if ($NewTorinoScale !== null) {
                        $updateData['torino_scale'] = $NewTorinoScale;
                    }

                    if (!empty($updateData)) {
                        FavoriteAsteroid::updateOrCreate(
                            [
                                'user_id' => auth()->id(),
                                'asteroid_id' => $asteroid->asteroid_id,
                                'asteroid_designation' => $asteroid->asteroid_designation,
                            ],
                            $updateData
                        );
                    }

                    // Invia la notifica
                    $user->notify(new FavoriteAsteroidUpdated($asteroid, implode(', ', $changes)));
                }
            }

            //dd('Ciao');
            return response()->json(['status' => 'Controllo completato']);
        }

        public function markAsRead($id, $asteroid_id)
        {
            $notification = Auth::user()->notifications()->find($id);

            if ($notification && $notification->read_at === null) {
                $notification->markAsRead();
            }

            return redirect()->route('asteroid.show', ['id' => $asteroid_id]);
        }
    }
