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
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Overview Ricerca NEO</h1>

            <!-- Sommario -->
            <nav class="mb-6 text-gray-900">
                <h2 class="text-xl font-semibold mb-2">Sommario</h2>
                <ul class="list-disc list-inside space-y-1">
                    <li><a href="#piccoli-osservatori" class="text-blue-600 hover:underline">Ruolo dei Piccoli e Grandi Osservatori</a></li>
                    <li><a href="#monitoraggio-neo" class="text-blue-600 hover:underline">Monitoraggio dei NEO</a></li>
                    <li><a href="#validazione-orbite" class="text-blue-600 hover:underline">Il Processo di Validazione delle Orbite</a></li>
                    <li><a href="#sistemi-allerta" class="text-blue-600 hover:underline">Sistemi di Allerta e Monitoraggio</a></li>
                    <li><a href="#recupero-meteoriti" class="text-blue-600 hover:underline">Recupero di Meteoriti</a></li>
                </ul>
            </nav>

            <div class="text-gray-900 space-y-4 text-justify">
                <section id="piccoli-osservatori">
                    <p class="text-lg">
                        <strong>Ruolo dei Piccoli e Grandi Osservatori</strong>
                    </p>

                    <p>
                        La tecnologia disponibile permette di scoprire e studiare questi corpi celesti con telescopi più piccoli,
                        completando osservazioni che in passato richiedevano strumenti molto più grandi. Con l’avanzare della
                        tecnologia digitale, l’aumento della sensibilità delle camere e la riduzione dei costi, queste ricerche sono
                        diventate ancora più accessibili. Oggi, chiunque abbia le competenze necessarie può contribuire al campo,
                        grazie agli strumenti, alle tecniche e ai software disponibili.
                    </p>

                    <p>
                        I piccoli osservatori svolgono un ruolo cruciale nel monitoraggio dei NEO grazie alla loro elevata reattività.
                        A differenza degli osservatori professionali, che seguono programmi di osservazione rigidamente pianificati,
                        un piccolo osservatorio può essere operativo in meno di mezz’ora e dedicare tutto il tempo necessario a un
                        oggetto specifico. Questo è fondamentale per tracciare corpi celesti che si muovono a velocità elevate e
                        raccogliere dati sufficienti a definirne l’orbita con precisione.
                    </p>

                    <p>
                        Per ottenere un codice di osservatorio ufficiale (ad esempio, M63), è necessario superare un esame di
                        certificazione e dimostrare la capacità di effettuare misurazioni accurate. Una volta ottenuto il codice,
                        l’osservatorio può inviare dati direttamente al Minor Planet Center, contribuendo attivamente alla ricerca e
                        alla sicurezza spaziale.
                    </p>

                    <p>
                        L’automazione ha reso il lavoro astronomico ancora più efficiente: oggi, grazie alla robotizzazione, è
                        possibile programmare osservazioni con script senza la necessità di essere fisicamente presenti. Questo ha
                        aumentato notevolmente la produttività, consentendo di raccogliere grandi quantità di dati in una sola notte,
                        a condizione che l’hardware risponda correttamente.
                    </p>

                    <p>
                        Se un oggetto ha una magnitudine molto bassa, solo i grandi osservatori possono osservarlo con strumenti
                        adeguati.
                    </p>

                    <p>
                        Il monitoraggio degli asteroidi viene effettuato automaticamente da grandi telescopi, che seguono gli oggetti
                        appena scoperti. Se un asteroide già noto entra nel campo visivo di un telescopio, vengono effettuate nuove
                        misurazioni per aggiornare la sua orbita.
                    </p>

                    <p>
                        Un elemento fondamentale nella sorveglianza spaziale è la distribuzione geografica degli osservatori. Se tutti
                        gli osservatori si trovassero nella stessa regione, ci sarebbero lunghi intervalli di tempo senza
                        misurazioni. Per ovviare a questo problema, gli osservatori sono dislocati in diverse longitudini (ad esempio,
                        Tokyo e l’Europa), garantendo una copertura quasi continua. Anche in caso di condizioni meteo avverse in
                        un’area, un altro osservatorio potrebbe avere un cielo sereno e raccogliere i dati necessari.
                    </p>

                    <p>
                        L’analisi orbitale avanzata si basa su simulazioni eseguite su grandi calcolatori, che permettono di
                        considerare le perturbazioni gravitazionali esercitate dai pianeti. Questo lavoro è gestito da specialisti
                        del Minor Planet Center, i quali raffinano continuamente le orbite grazie a nuovi dati osservativi.
                    </p>
                </section>

                <section id="monitoraggio-neo">
                    <p class="text-lg"><strong>Monitoraggio dei NEO</strong></p>
                    <p>
                        Sul sito del Minor Planet Center è disponibile una lista di oggetti con parametri orbitali incerti
                        (<a href="https://minorplanetcenter.net/iau/NEO/toconfirm_tabular.html" class="text-blue-600 underline" target="_blank">link</a>),
                        alcuni appena scoperti e altri noti da pochi giorni. Per determinare un’orbita stabile, sono necessarie
                        numerose misurazioni. Un punteggio ("score") indica la probabilità che un oggetto sia un Near-Earth Object (NEO).
                        Gli osservatori inseriscono il codice internazionale dell’oggetto da monitorare ed ottengono le effemeridi di
                        quell’asteroide specifico, mentre il parametro <strong>Nobs</strong> indica il numero di osservazioni raccolte
                        fino a quel momento. In questa fase iniziale, le incertezze sono molto elevate.
                    </p>

                    <p>
                        Per ottenere un'orbita precisa, è necessario raccogliere rapidamente un gran numero di immagini senza introdurre
                        distorsioni. La velocità degli oggetti aumenta man mano che si avvicinano alla Terra, rendendoli difficili da
                        seguire con i telescopi. Il breve intervallo temporale disponibile per l'osservazione è cruciale: se l'oggetto
                        è troppo veloce, può uscire dal campo visivo prima che ulteriori misurazioni possano essere effettuate.
                    </p>

                    <p>
                        Data la rapidità con cui questi oggetti si muovono, l’hardware del telescopio deve essere performante e il
                        software estremamente robusto per raccogliere e trasmettere misurazioni affidabili. Le misure vengono inviate
                        al centro di controllo internazionale, che le confronta con i dati provenienti da altri osservatori sparsi nel
                        mondo per determinare l’orbita dell’oggetto.
                    </p>

                    <p>
                        Se l'orbita risultasse coincidente con quella terrestre, verrebbero attivati vari sistemi di allerta globale.
                        Alcuni astrofisici si dedicano volontariamente a monitorare specifici asteroidi, selezionandoli in base alla
                        visibilità dall’osservatorio. Nel caso in cui un oggetto venga classificato come <strong>Potentially Hazardous Asteroid (PHA)</strong>,
                        l’allerta viene estesa a tutta la comunità astronomica.
                    </p>

                    <p>
                        Con il tempo, se emergono dati aggiuntivi, si possono recuperare osservazioni passate, da archivio, per affinare
                        i calcoli e ridurre le probabilità di impatto. Grazie alla precisione crescente dei modelli, oggi è possibile
                        prevedere con esattezza il punto di caduta di alcuni meteoriti, consentendo agli osservatori terrestri di recuperarli.
                    </p>

                    <p>
                        Anche le comete vengono monitorate con un processo simile, ma il loro studio è più complesso a causa della natura
                        diffusa della loro chioma e coda, che rende difficile il calcolo preciso dell’orbita. Tuttavia, anch’esse possono
                        rappresentare un rischio e devono essere tenute sotto controllo.
                    </p>

                    <p>
                        Gli elementi orbitali e i residui delle misurazioni vengono pubblicati nelle
                        <a href="https://minorplanetcenter.org/mpec/RecentMPECs.html" class="text-blue-600 underline" target="_blank">MPECs (Minor Planet Electronic Circulars)</a>,
                        dove ogni giorno vengono elencati gli oggetti studiati e i risultati ottenuti. Da questi elementi orbitali e
                        dai residui si riescono a calcolare le effemeridi. L’orbita di questi corpi celesti è in costante affinamento
                        e non può essere determinata con precisione assoluta a lungo termine, a causa delle perturbazioni gravitazionali
                        di pianeti come Giove. Questo impone un monitoraggio continuo, dato che le proiezioni future possono cambiare.
                    </p>

                    <p>
                        Un caso emblematico è quello di <strong>Apophis</strong>, inizialmente considerato un oggetto pericoloso ma
                        successivamente declassato grazie a misurazioni più precise. Tuttavia, continua a essere osservato perché esiste
                        una possibilità, seppur remota, che nel 2034 possa entrare in una <strong>risonanza gravitazionale</strong>
                        con la Terra, passando tra i satelliti geostazionari. Le sue misurazioni vengono costantemente aggiornate per
                        ridurre l’incertezza sulla sua traiettoria.
                    </p>
                </section>

                <section id="validazione-orbite">
                    <p class="text-lg"><strong>Il Processo di Validazione delle Orbite</strong></p>
                    <p>
                        I dati raccolti dagli osservatori vengono inviati al <strong>Minor Planet Center</strong>, che li valida e li utilizza
                        nei software di calcolo orbitale. Grazie a questi sistemi, l’orbita di un oggetto viene aggiornata quasi in
                        tempo reale. Se le nuove misurazioni indicano un potenziale rischio di impatto, viene generato un allarme automatico,
                        che mobilita rapidamente tutti gli osservatori disponibili.
                    </p>

                    <p>
                        Un aspetto cruciale è la <strong>riduzione dell’incertezza orbitale</strong>. Inizialmente, la traiettoria di un
                        asteroide può essere rappresentata come una "ciambella" molto ampia, all'interno della quale l’oggetto potrebbe
                        trovarsi in qualsiasi punto. Con il tempo e con nuove misurazioni, questa incertezza si restringe fino a diventare
                        un "tubicino" o, nel migliore dei casi, una linea precisa. L’orbita, solitamente, ha una forma ellittica.
                    </p>

                    <p>
                        Per esempio, un’incertezza di oltre <strong>100.000 km</strong>, con la Terra all'interno di questa regione di
                        incertezza, provoca alcune preoccupazioni iniziali. Solo con ulteriori osservazioni può risultare possibile
                        escludere un impatto, riducendo radicalmente i 100.000 km di incertezza.
                    </p>

                </section>

                <section id="sistemi-allerta">
                    <p class="text-lg">
                        <strong>Sistemi di Allerta e Monitoraggio</strong>
                    </p>

                    <p>
                        Esistono convenzioni internazionali, come le scale di Torino e Palermo, che stabiliscono protocolli di risposta agli impatti asteroidali. Finora non è stato necessario allertare la popolazione, ma è inevitabile che, prima o poi, un impatto avverrà. Tuttavia, gli oggetti di dimensioni che provocherebbero una distruzione a livello regionale sono già stati scoperti, riducendo il rischio di sorprese.
                    </p>

                    <p>
                        Il sistema di sorveglianza è estremamente efficiente, con la scoperta di circa 30-40 nuovi asteroidi al giorno. Tuttavia, un punto critico rimane la direzione di provenienza degli oggetti: se un asteroide arrivasse dalla direzione del Sole, potrebbe non essere rilevato in tempo. Questo è quanto accaduto nel caso del meteorite di Čeljabinsk, che esplose nell’atmosfera terrestre senza preavviso.
                    </p>

                    <p>
                        Un altro problema è la mancanza di consapevolezza del rischio. Molti sottovalutano il pericolo degli impatti cosmici, nonostante la Luna e altri corpi celesti mostrino chiaramente segni di impatti recenti. Anche se la Luna non ha atmosfera, risultando quindi più suscettibile a impatti, questo non risulta un motivo valido per non alzare le misure di precauzione disponibili.
                    </p>
                </section>

                <section id="recupero-meteoriti">
                    <p class="text-lg">
                        <strong>Recupero di Meteoriti</strong>
                    </p>

                    <p>
                        Il principio di scoperta del luogo i impatto di un asteroide è simile a quello che ci consente di prevedere con estrema precisione le eclissi per i prossimi cento anni (e oltre). Le orbite e le rotazioni della Terra, della Luna e dei principali pianeti del Sistema Solare sono infatti ben conosciute. Disponiamo di database e modelli in grado di calcolare con precisione la posizione dei corpi celesti in qualsiasi momento futuro, semplicemente inserendo una data a scelta.
                    </p>

                    <p>
                        Quando si tratta di un asteroide, però, la situazione è più complessa. Le informazioni a nostra disposizione sono meno dettagliate rispetto a quelle degli altri corpi celesti. Anche se riusciamo a stimare con buona accuratezza la sua orbita attorno al Sole, una minima incertezza nei dati – anche una frazione di percentuale – può determinare l’impossibilità di stabilire con certezza se l’oggetto colpirà la Terra o meno. Se un impatto è confermato, l’analisi successiva determina il punto esatto di caduta, con una precisione sempre maggiore. Grazie a modelli avanzati, si riesce a prevedere la traiettoria dell'oggetto fino al punto d'impatto con margini d’errore ridottissimi.
                    </p>

                    <p>
                        Puoi calcolare molti parametri con estrema precisione (se, quando e dove impatterà, con quale velocità, con quale distribuzione dei frammenti, …)
                    </p>

                    <p>
                        Un caso notevole è il software sviluppato da Carbognani, che utilizza tecniche di triangolazione per individuare la traiettoria dei meteoriti in caduta. Grazie a questo sistema Prisma, è possibile restringere il punto di impatto a una zona di pochi chilometri, consentendo il recupero dei frammenti. Non è più una questione legata allo stato dive cadrà l’asteroide, ma si riesce a ricavare il luogo preciso di impatto di piccoli frammenti. Se il meteorite ha una dimensione elevata, invece, si riesce quasi a mettere un bersaglio su questo Meteorite.
                    </p>

                    <p>
                        Ad esempio, nel caso del meteorite di Matera, le previsioni erano talmente precise che uno dei frammenti è stato trovato su un'abitazione. Questo dimostra quanto la raccolta e l’analisi dei dati siano fondamentali per la scienza.
                    </p>

                </section>


            </div>
        </div>
    </section>
</main>

</body>
</html>

<script>
    document.getElementById('menu-overview')?.setAttribute('data-selected', 'true');
</script>
