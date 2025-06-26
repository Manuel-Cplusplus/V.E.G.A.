{{--
    V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
    Copyright (c) 2025 Manuel Carlucci

    Licensed under CC BY-NC-SA 4.0: http://creativecommons.org/licenses/by-nc-sa/4.0/
--}}

    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('layouts.head')

<body class="antialiased min-h-screen bg-gray-100">
@include('layouts.header')

<main class="flex min-h-screen">
    <!-- Sidebar sinistra -->
    @include('theory.sidebar')

    <!-- Sezione contenuti destra -->
    <section class="flex-1 p-8">
        <div id="content-area" class="bg-white shadow-lg rounded-lg p-6 opacity-80">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Glossario</h1>

            <details>
                <summary class="cursor-pointer text-black hover:underline">Ablazione</summary>
                <p class="text-gray-600 mt-2">Giunto ad una quota di 80-90 km (mesosfera), la temperatura del meteoroide raggiunge i 2500 K ed inizia la sublimazione degli atomi del corpo celeste. Questo processo di perdita di massa è noto come ablazione.
                    Percorso seguito da un corpo celeste attorno a un altro sotto l'effetto della gravità.
                </p>
                <a href = "https://ntrs.nasa.gov/api/citations/19690013568/downloads/19690013568.pdf" target="_blank" class="text-blue-500 hover:underline">Approfondisci</a>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">Albedo</summary>
                <p class="text-gray-600 mt-2">L'albedo è una misura della percentuale di luce solare riflessa da una superficie.</p>
                <a href = "https://www.earthdata.nasa.gov/topics/land-surface/albedo" target="_blank" class="text-blue-500 hover:underline">Approfondisci</a>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">Asteroide</summary>
                <p class="text-gray-600 mt-2">Corpo roccioso di dimensioni variabili principalmente concentrato nella fascia degli asteroidi tra Marte e Giove.</p>
                <a href = "https://science.nasa.gov/solar-system/asteroids/" target="_blank" class="text-blue-500 hover:underline">Approfondisci</a>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">Asteroide Potenzialmente Pericoloso</summary>
                <p class="text-gray-600 mt-2">Gli asteroidi potenzialmente pericolosi (PHA) sono attualmente definiti in base a parametri che misurano la capacità dell'asteroide di effettuare avvicinamenti minacciosi alla Terra.</p>
                <a href = "https://cneos.jpl.nasa.gov/about/neo_groups.html" target="_blank" class="text-blue-500 hover:underline">Approfondisci</a>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">Distanza Lunare (LD)</summary>
                <p class="text-gray-600 mt-2">Il termine LD (Distanza Lunare) si riferisce alla distanza media tra la Terra e la Luna. Per i dati riportati su questo sito, utilizziamo un semiasse maggiore medio per la Luna di 384400 km (~0,002570 UA) per definire una LD.</p>
                <a href = "https://cneos.jpl.nasa.gov/glossary/LD.html" target="_blank" class="text-blue-500 hover:underline">Fonte</a>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">Eccentricità</summary>
                <p class="text-gray-600 mt-2">Un parametro orbitale che descrive l'eccentricità dell'ellisse orbitale.</p>
                <a href = "https://ssd.jpl.nasa.gov/glossary/eccentricity.html" target="_blank" class="text-blue-500 hover:underline">Fonte</a>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">Effemeridi</summary>
                <p class="text-gray-600 mt-2">Un'effemeride è una tabella di posizioni e velocità calcolate (e/o di varie grandezze derivate come ascensione retta e declinazione) di un corpo in orbita in momenti specifici.</p>
                <a href = "https://ssd.jpl.nasa.gov/glossary/ephemeris.html" target="_blank" class="text-blue-500 hover:underline">Fonte</a>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">Energia di impatto</summary>
                <p class="text-gray-600 mt-2">Energia rilasciata da un asteroide in caso di impatto con la Terra, misurata in kilotoni o megatoni di TNT.</p>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">Energia irradiata</summary>
                <p class="text-gray-600 mt-2">Energia luminosa effettivamente emessa durante l’evento osservato nell’atmosfera. Si basa sulle osservazioni reali, cioè quanta luce ha irradiato l'evento.</p>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">Fireballs</summary>
                <p class="text-gray-600 mt-2">Palle di fuoco e bolidi sono termini astronomici che indicano meteore eccezionalmente luminose, così spettacolari da essere visibili su un'area molto ampia. </p>
                <a href = "https://cneos.jpl.nasa.gov/fireballs/intro.html" target="_blank" class="text-blue-500 hover:underline">Fonte</a>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">Magnitudine Assoluta (h)</summary>
                <p class="text-gray-600 mt-2">La magnitudine assoluta di un asteroide è la magnitudine visuale che un osservatore registrerebbe se l'asteroide fosse posto a 1 Unità Astronomica (UA) di distanza e a 1 UA dal Sole e con un angolo di fase zero.</p>
                <a href = "https://cneos.jpl.nasa.gov/glossary/h.html" target="_blank" class="text-blue-500 hover:underline">Fonte</a>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">Meteoroidi</summary>
                <p class="text-gray-600 mt-2">Queste rocce si trovano ancora nello spazio. Le dimensioni dei meteoroidi variano da granelli di polvere a piccoli asteroidi.</p>
                <a href = "https://science.nasa.gov/solar-system/meteors-meteorites/" target="_blank" class="text-blue-500 hover:underline">Approfondimento</a>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">Meteore</summary>
                <p class="text-gray-600 mt-2">Quando i meteoroidi entrano nell'atmosfera terrestre (o in quella di un altro pianeta, come Marte) ad alta velocità e bruciano, le palle di fuoco o "stelle cadenti" vengono chiamate meteore.</p>
                <a href = "https://science.nasa.gov/solar-system/meteors-meteorites/" target="_blank" class="text-blue-500 hover:underline">Approfondimento</a>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">Meteorite</summary>
                <p class="text-gray-600 mt-2">Quando un meteoroide sopravvive al viaggio attraverso l'atmosfera e colpisce il suolo, viene chiamato meteorite.</p>
                <a href = "https://science.nasa.gov/solar-system/meteors-meteorites/" target="_blank" class="text-blue-500 hover:underline">Approfondimento</a>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">Near-Earth-Object (NEO)</summary>
                <p class="text-gray-600 mt-2">Oggetto vicino alla Terra, come asteroidi o comete, con un'orbita che lo porta entro 1,3 UA dal Sole.</p>
                <a href = "https://cneos.jpl.nasa.gov/about/basics.html" target="_blank" class="text-blue-500 hover:underline">Approfondimento</a>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">Orbita</summary>
                <p class="text-gray-600 mt-2">Percorso seguito da un corpo celeste attorno a un altro sotto l'effetto della gravità.</p>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">Scala Palermo</summary>
                <p class="text-gray-600 mt-2">La scala Palermo è una scala logaritmica usata dagli astronomi per valutare il rischio di impatto di un oggetto di tipo NEO. Usata da esperti.</p>
                <a href = "https://cneos.jpl.nasa.gov/sentry/palermo_scale.html" target="_blank" class="text-blue-500 hover:underline">Approfondisci</a>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">Scala Torino</summary>
                <p class="text-gray-600 mt-2">La scala Torino è un metodo di classificazione del pericolo di impatto associato agli oggetti di tipo NEO (near-Earth object), come asteroidi e comete. Usato principalmente per comunicazione di pericolosità col pubblico.</p>
                <a href = "https://cneos.jpl.nasa.gov/sentry/torino_scale.html" target="_blank" class="text-blue-500 hover:underline">Approfondisci</a>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">Sentry</summary>
                <p class="text-gray-600 mt-2">Sentry è un sistema di monitoraggio delle collisioni altamente automatizzato che analizza costantemente il catalogo di asteroidi più aggiornato per individuare possibili impatti futuri con la Terra nei prossimi 100 anni.</p>
                <a href = "https://cneos.jpl.nasa.gov/sentry/intro.html" target="_blank" class="text-blue-500 hover:underline">Approfondisci</a>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">Unità Astronomica (UA)</summary>
                <p class="text-gray-600 mt-2">Le unità astronomiche, abbreviate in UA, sono un'utile unità di misura all'interno del nostro sistema solare e sono pari a 149,597,870,700 m.</p>
                <a href = "https://science.nasa.gov/solar-system/cosmic-distances/" target="_blank" class="text-blue-500 hover:underline">Approfondisci</a>
            </details>


            <!-- Dettagli Tecnici -->
            <h1 class="text-2xl font-bold text-gray-800 mb-4 mt-4">Dettagli Tecnici</h1>
            <p class="text-gray-700 mb-6">
                I seguenti parametri descrivono le caratteristiche orbitali dell'asteroide, la precisione delle osservazioni e la classificazione dinamica. Sono fondamentali per determinare il comportamento dell’oggetto nello spazio e la sua possibile interazione con la Terra.
            </p>
            <!-- Parametri orbitali -->
            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">orbit_uncertainty</summary>
                <p class="text-gray-600 mt-2">Indicatore dell'incertezza sull'orbita: valori bassi indicano maggiore affidabilità.</p>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">minimum_orbit_intersection</summary>
                <p class="text-gray-600 mt-2">Distanza minima tra l'orbita dell'oggetto e quella terrestre (MOID).</p>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">jupiter_tisserand_invariant</summary>
                <p class="text-gray-600 mt-2">Parametro che descrive la relazione dinamica tra l'oggetto e Giove.</p>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">semi_major_axis</summary>
                <p class="text-gray-600 mt-2">Distanza media dal Sole espressa come semiasse maggiore (in UA).</p>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">inclination</summary>
                <p class="text-gray-600 mt-2">Angolo tra il piano orbitale dell'oggetto e l'eclittica.</p>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">ascending_node_longitude</summary>
                <p class="text-gray-600 mt-2">Posizione angolare del nodo ascendente rispetto al riferimento celeste.</p>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">orbital_period</summary>
                <p class="text-gray-600 mt-2">Tempo impiegato dall'oggetto per completare un'orbita attorno al Sole.</p>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">perihelion_distance</summary>
                <p class="text-gray-600 mt-2">Distanza più vicina al Sole raggiunta dall'oggetto.</p>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">aphelion_distance</summary>
                <p class="text-gray-600 mt-2">Distanza più lontana dal Sole raggiunta durante l'orbita.</p>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">perihelion_argument</summary>
                <p class="text-gray-600 mt-2">Angolo tra il nodo ascendente e il perielio.</p>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">perihelion_time</summary>
                <p class="text-gray-600 mt-2">Tempo (espresso in giorni giuliani) in cui l'oggetto raggiunge il perielio.</p>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">mean_anomaly</summary>
                <p class="text-gray-600 mt-2">Posizione media teorica dell'oggetto sull'orbita in un dato istante.</p>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">mean_motion</summary>
                <p class="text-gray-600 mt-2">Velocità angolare media del moto orbitale (in gradi/giorno).</p>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">equinox</summary>
                <p class="text-gray-600 mt-2">Sistema di riferimento astronomico per le coordinate orbitali.</p>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">orbit_class_type</summary>
                <p class="text-gray-600 mt-2">Categoria generale dell'orbita (es. Aten, Apollo, Amor).</p>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">orbit_class_range</summary>
                <p class="text-gray-600 mt-2">Intervallo di valori orbitali che definiscono la classe dell'oggetto.</p>
            </details>


            <!-- Metodi di Analisi -->
            <h1 class="text-2xl font-bold text-gray-800 mb-4 mt-4">Metodi di Analisi</h1>
            <p class="text-gray-700 mb-6">
                I metodi di analisi orbitale avanzata servono a valutare l’incertezza e la probabilità di impatto di un oggetto, tenendo conto delle variazioni dinamiche, delle simulazioni Monte Carlo e dei filtri IOBS.
            </p>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">LOV (Line of Variation)</summary>
                <p class="text-gray-600 mt-2">Linea che rappresenta le variazioni possibili dell’orbita secondo le incertezze osservative. I parametri LOV (come <strong>dist, width, sigma_lov</strong>) permettono di valutare se esistono traiettorie che impattano la Terra all’interno dell’intervallo d’incertezza.</p>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">MC (Monte Carlo)</summary>
                <p class="text-gray-600 mt-2">Simulazione di migliaia di orbite possibili, tenendo conto delle incertezze. Il parametro <strong>sigma_mc</strong> indica quanto l’orbita simulata si discosta dalla nominale, utile per stimare la probabilità d’impatto.</p>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">IOBS (Impact Orbit Filtering System)</summary>
                <p class="text-gray-600 mt-2">Algoritmo che filtra le orbite potenzialmente impattanti. Il parametro <strong>sigma_vi</strong> misura la distanza nominale tra l’orbita e le condizioni necessarie per un impatto.</p>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">dist (rE)</summary>
                <p class="text-gray-600 mt-2">Distanza tra la Linea delle Variazioni (LOV) e il centro della Terra, espressa in raggi terrestri.</p>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">width (rE)</summary>
                <p class="text-gray-600 mt-2">Semi-larghezza della regione di incertezza lungo la LOV, in raggi terrestri.</p>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">sigma_imp</summary>
                <p class="text-gray-600 mt-2">Distanza laterale, in deviazioni standard, tra la LOV e la Terra. Un valore pari a 0 indica impatto diretto.</p>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">sigma_lov</summary>
                <p class="text-gray-600 mt-2">Posizione lungo la LOV; 0 rappresenta l'orbita nominale più probabile.</p>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">stretch (rE/sigma)</summary>
                <p class="text-gray-600 mt-2">Allungamento della regione di incertezza lungo la LOV: valori alti indicano minore probabilità d'impatto.</p>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">sigma_mc</summary>
                <p class="text-gray-600 mt-2">Deviazione rispetto all'orbita nominale stimata attraverso simulazioni Monte Carlo.</p>
            </details>

            <details>
                <summary class="mt-2 cursor-pointer text-black hover:underline">sigma_vi</summary>
                <p class="text-gray-600 mt-2">Deviazione orbitale calcolata con il metodo IOBS per valutare il rischio d'impatto.</p>
            </details>

        </div>
    </section>
</main>

</body>
</html>

<script>
    // Funzione per evidenziare il menu attivo
    document.getElementById('menu-glossario')?.setAttribute('data-selected', 'true');
</script>

