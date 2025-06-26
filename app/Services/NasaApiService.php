<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

/**
 * V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
 * Copyright (c) 2025 Manuel Carlucci
 *
 * This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/4.0/
 */

class NasaApiService
{
    /**
     * Recupera i dati dall'API NASA.
     *
     * @param string $apiName Il nome dell'API (es. NeoWS, CAD, Fireball).
     * @param array $params I parametri della richiesta API.
     * @return array|null I dati ricevuti o null in caso di errore.
     */
    public function fetchData(string $apiName, array $params): ?array
    {
        //dd($params);
        $endpoint = $this->resolveEndpoint($apiName);
        if (!$endpoint) {
            return null;
        }

        // Ottieni l'API key (necessaria solo per NeoWs)
        $apiKey = $this->getApiKey();

        // Gestione parametri specifici per CAD
        if ($apiName === 'CAD') {
            $params['dist-min'] = $params['dist-min'] ?? '0';
            $params['dist-max'] = $params['dist-max'] ?? '100';
            $params['date-min'] = $params['date-min'] ?? '1900-01-01';
            $params['date-max'] = $params['date-max'] ?? '2100-01-01';
        }

        // Gestione endpoint con ID dinamico per NeoWS_ID
        if ($apiName === 'NeoWS_ID' && isset($params['id'])) {
            $id = $params['id'];
            unset($params['id']);
            $url = "https://{$endpoint}/{$id}?api_key={$apiKey}";
        } else {
            // Aggiungi la chiave API solo se richiesta (NeoWS e NeoWS_ID)
            if (in_array($apiName, ['NeoWS', 'NeoWS_ID'])) {
                $params['api_key'] = $apiKey;
            }

            $url = "https://{$endpoint}?" . http_build_query($params);
        }

        // Esegui la chiamata disattivando verifica SSL solo per CAD/Fireball/Sentry
        $sslExceptions = ['CAD', 'Fireball', 'Sentry'];
        $http = in_array($apiName, $sslExceptions)
            ? Http::withOptions(['verify' => false])
            : Http::withOptions([]);

        //dd($url);
        $response = $http->get($url);
        //dd($response);
        return $response->json();
    }


    /**
     * Risolve l'endpoint dell'API in base al nome dell'API.
     *
     * @param string $apiName
     * @return string|null
     */
    protected function resolveEndpoint(string $apiName): ?string
    {
        $endpoints = [
            'NeoWS' => 'api.nasa.gov/neo/rest/v1/feed',
            'NeoWS_ID' => 'api.nasa.gov/neo/rest/v1/neo', // Singolo ID
            'CAD' => 'ssd-api.jpl.nasa.gov/cad.api',
            'Fireball' => 'ssd-api.jpl.nasa.gov/fireball.api',
            'Sentry' => 'ssd-api.jpl.nasa.gov/sentry.api',
        ];

        return $endpoints[$apiName] ?? null;
    }

    /**
     * Recupera la chiave API.
     * Questa funzione può essere facilmente modificata per recuperare la chiave API dal database
     * in futuro, quando l'autenticazione dell'utente sarà implementata.
     *
     * @return string
     */
    private function getApiKey(): string
    {
        // Temporaneamente si recupera dalla variabile .env
        // return env('NASA_API_KEY');
        return Auth::check() && Auth::user()->NASA_API_KEY
            ? Crypt::decryptString(Auth::user()->NASA_API_KEY) // Decrittografa la chiave API
            : 'DEMO_KEY';
    }

    /**
     * Recupera i dettagli dei limiti delle richieste API.
     *
     * @return array|null
     */
    public function getApiUsage(): ?array
    {
        // Richiesta a endpoint casuale per il recupero dei dati
        $response = Http::get('https://api.nasa.gov/neo/rest/v1/feed', [
            'api_key' => $this->getApiKey()
        ]);

        //dd($response->headers());

        // Restituisci i dettagli dei limiti delle richieste
        if ($response->successful()) {
            return [
                'limit' => $response->header('X-RateLimit-Limit'),
                'remaining' => $response->header('X-RateLimit-Remaining'),
            ];
        }

        return null; // Restituisci null se la richiesta fallisce
    }
}
