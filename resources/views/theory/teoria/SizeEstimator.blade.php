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
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Estimatore di Dimensioni Asteroide</h1>

            <div class="text-gray-700 space-y-4 text-justify">
                <p>
                    Solo una piccola parte degli asteroidi ha dimensioni e forme note con precisione. La maggior parte presenta geometrie irregolari, e solo pochi risultano quasi sferici. Tuttavia, è possibile stimare una dimensione equivalente sferica (diametro) utilizzando la magnitudine assoluta (H) e un albedo geometrico presunto (a).
                </p>
                <p>
                    La formula più comune per stimare il diametro <em>d</em> (in chilometri) è:
                </p>
                <pre class="bg-gray-100 p-3 rounded text-sm overflow-x-auto"><code>d = 10^(3.1236 - 0.5 * log10(a) - 0.2 * H)</code></pre>
                <p>
                    Questa espressione assume che l’asteroide sia una sfera con superficie uniforme, ma nella realtà tale approssimazione introduce incertezze, legate soprattutto a H (±0.5 mag) e al valore presunto dell’albedo.
                </p>
                <p>
                    Per gli asteroidi della fascia principale, si osserva una correlazione tra albedo e classe spettrale. Ad esempio, le classi C/G/B/F/P/D hanno albedo medi intorno al 6%, mentre le classi S/A/L mostrano albedo medi attorno al 20%. Tuttavia, per i NEA, la classe tassonomica è spesso ignota, quindi si assume un valore medio convenzionale di 0.14. Con questa ipotesi, un NEA sferico con H ≈ 17.75 ha un diametro di circa 1 km.
                </p>
                <p>
                    Le tecniche per stimare le dimensioni degli asteroidi si dividono in:
                </p>
                <ul class="list-disc list-inside">
                    <li><strong>Metodi diretti:</strong> missioni spaziali, immagini con ottica adattiva, osservazioni radar, occultazioni stellari (applicabili solo a oggetti grandi o vicini).</li>
                    <li><strong>Metodi indiretti:</strong> polarimetria (per stimare l’albedo) e modellazione termica (basata su dati visibili/infrarossi per stimare diametro e albedo).</li>
                </ul>
                <p>
                    La magnitudine assoluta H è ottenuta da osservazioni fotometriche, ma richiede una correzione per l’angolo di fase solare, solitamente applicata tramite il modello H–G (Bowell et al., 1989), con due parametri liberi: H e il parametro di pendenza G.
                </p>
                <p>
                    Analisi su un campione di 583 oggetti hanno evidenziato uno scostamento sistematico nei valori di H nei cataloghi orbitali. Inoltre, la tendenza apparente di un aumento dell’albedo con la diminuzione del diametro (osservata nei dati WISE preliminari per asteroidi S-type tra 5 e 30 km) viene meno quando si applicano valori di H corretti.
                </p>
                <p>
                    In conclusione, pur con le incertezze legate a H e all’albedo, queste stime risultano comunque preziose per monitorare l’evoluzione delle scoperte NEA nel tempo.
                </p>
            </div>

            <!-- Riferimenti -->
            <div class="mt-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Riferimenti</h2>
                <ul class="list-disc list-inside space-y-2">
                    <li><a href="https://cneos.jpl.nasa.gov/stats/" class="text-blue-600 hover:underline">Discovery Statistics</a></li>
                    <li><a href="https://cneos.jpl.nasa.gov/tools/ast_size_est.html" class="text-blue-600 hover:underline">Asteroid Size Estimator</a></li>
                    <li><a href="https://www.sciencedirect.com/science/article/abs/pii/S0019103512003028?via%3Dihub" class="text-blue-600 hover:underline">Absolute magnitudes of asteroids and a revision of asteroid albedo estimates from WISE thermal observations</a></li>
                </ul>
            </div>
        </div>
    </section>
</main>

</body>
</html>

<script>
    document.getElementById('menu-size')?.setAttribute('data-selected', 'true');
</script>
