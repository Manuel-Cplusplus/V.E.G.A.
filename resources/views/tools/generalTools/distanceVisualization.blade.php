{{--
    V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
    Copyright (c) 2025 Manuel Carlucci

    Licensed under CC BY-NC-SA 4.0: http://creativecommons.org/licenses/by-nc-sa/4.0/
--}}

<script type="importmap">
    {
        "imports": {
            "three": "https://cdn.jsdelivr.net/npm/three@0.158.0/build/three.module.js",
            "three/examples/jsm/controls/OrbitControls.js": "https://cdn.jsdelivr.net/npm/three@0.158.0/examples/jsm/controls/OrbitControls.js",
            "three/examples/jsm/loaders/TextureLoader.js": "https://cdn.jsdelivr.net/npm/three@0.158.0/examples/jsm/loaders/TextureLoader.js"
        }
    }
</script>


<!-- Modale -->
<div id="DistancePopup" class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50 hidden">
    <div class="bg-gray-800 rounded-xl shadow-lg p-4 relative w-full max-w-6xl h-[600px] flex">
        <button onclick="closeDistancePopup()" class="absolute top-3 right-3 bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
            Chiudi
        </button>
        <!-- Loading overlay -->
        <div id="loadingOverlay" class="absolute inset-0 bg-black bg-opacity-70 flex items-center justify-center z-10 rounded-lg">
            <div class="text-center text-white">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-500 mb-2"></div>
                <p>Caricamento modelli 3D...</p>
                <p> In caso di caricamento prolungato aggiornare la pagina.</p>
            </div>
        </div>
        <div class="w-3/4 h-full relative">
            <canvas id="sceneCanvas" class="w-full h-full rounded-lg"></canvas>
            <div class="absolute bottom-4 left-4 bg-gray-800  p-2 rounded text-white">
                <p class="text-sm">Clicca sui corpi celesti per selezionarli</p>
                <p class="text-sm">Rotella: zoom | Trascina: rotazione | Shift+Trascina: traslazione</p>
            </div>
        </div>
        <div id="infoPanel" class="w-1/4 h-full bg-white text-gray-800 p-4 ml-4 rounded-lg overflow-auto">
            <h2 id="objectName" class="text-xl font-bold mb-2">Seleziona un oggetto</h2>
            <div id="objectInfo" class="text-sm">
                Clicca su Terra, Luna o Asteroide per visualizzare le informazioni.
            </div>
        </div>
    </div>
</div>

<!-- THREE.js -->
<script type="module">
    import * as THREE from 'three';
    // Importa controlli dell'orbita che permettono di ruotare e zoomare la scena con il mouse.
    import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';


    /* Variabili principali
        scene: il contenitore 3D principale.
        camera: il punto di vista dell'osservatore.
        renderer: disegna la scena sulla canvas HTML.
        controls: per navigare nella scena con il mouse.
     */
    let scene, camera, renderer, controls, animationId;
    let earth, moon, asteroid, moonGlow, earthClouds, earthGlow;
    let earthGroup, moonGroup, asteroidGroup;
    // Selezione con il mouse
    let raycaster, mouse;
    let selectedObject = null;
    let ldValue = 0;
    // caricamento delle texture
    let loadingManager;
    let loadedItemCount = 0;
    let totalItemsToLoad = 3; // Numero totale di texture da caricare

    // Rotazione degli oggetti
    let earthRotationSystem, moonRotationSystem;

    // Variabili per il tracking dell'oggetto
    let followingObject = false;
    let objectToFollow = null;


    // Percorsi delle immagini usate per "rivestire" i modelli della Terra, Luna e asteroide.
    const texturePaths = {
        earth: {
            map: '/media/textures/earth/earth_daymap.jpg',
        },
        moon: {
            map: '/media/textures/moon/moon.jpg',
        },
        asteroid: {
            map: '/media/textures/asteroid/makemake_fictional.jpg',
        }
    };

    // Informazioni degli oggetti
    const objectData = {
        "Terra": {
            "Diametro": "12.756 km",
            "Massa": "5,972 × 10^24 kg",
            "Rotazione": "24 ore",
            "Orbita solare": "365,25 giorni",
            "Distanza dal Sole": "149,6 milioni km (1 UA)",
            "Atmosfera": "78% azoto, 21% ossigeno, 1% altri gas",
            "Satelliti": "1 (Luna)"
        },
        "Luna": {
            "Diametro": "3.474 km",
            "Massa": "7,348 × 10^22 kg",
            "Orbita terrestre": "27 giorni, 7 ore, 43 minuti",
            "Distanza dalla Terra": "384.400 km (1 LD)",
            "Composizione": "Rocce di origine effusiva, soprattutto silicati di alluminio, calcio, ferro, magnesio e ossidi",
            "Atmosfera": "Quasi assente",
        },
        "Asteroide": {
            "Nome": "{{ $asteroid['name'] ?? $asteroidData['name'] ?? 'Non disponibile' }}",
            "Diametro": "{{ isset($asteroid['diameter']) ? number_format((float)$asteroid['diameter'], 2) : (isset($asteroidData['diameter']) ? number_format((float)$asteroidData['diameter'], 2) : 'Non disponibile') }} m",
            "Velocità Attuale": "{{ isset($asteroid['velocity']) ? $asteroid['velocity'] . ' km/s' : (isset($asteroidData['velocity_km_s']) ? $asteroidData['velocity_km_s'] . ' km/s' : 'Non disponibile') }}",
            "Distanza dalla Terra": "{{isset($asteroid['miss_distance']) ? number_format((float)$asteroid['miss_distance'], 2)
                                    : (isset($asteroidData['miss_distance_lunar']) ? number_format((float)$asteroidData['miss_distance_lunar'] * 384400, 2) : 'Non disponibile')
                                    }} km ({{isset($asteroid['miss_distance_lunar']) ? number_format((float)$asteroid['miss_distance_lunar'], 2)
                                    : (isset($asteroidData['miss_distance_lunar'])  ? number_format((float)$asteroidData['miss_distance_lunar'], 2)  : 'N/D')}} LD)",            "Pericoloso": "{{ isset($asteroid['hazardous']) ? ($asteroid['hazardous'] ? 'Sì' : 'No') : (isset($asteroidData['is_hazardous']) ? ($asteroidData['is_hazardous'] ? 'Sì' : 'No') : 'Non disponibile') }}",
            "Possibile Impatto Futuro": "{{ isset($asteroid['is_sentry_object']) ? ($asteroid['is_sentry_object'] ? 'Sì' : 'No') : (isset($asteroidData['is_sentry_object']) ? ($asteroidData['is_sentry_object'] ? 'Sì' : 'No') : 'Non disponibile') }}"
        }

    };

    let globalLdValue = null;

    // Mostra un popup e fa partire la scena 3D, passando il valore ld (distanza in unità lunari).
    window.showDistancePopup = function(ld) {
        globalLdValue = ld;
        updateMissDistance(ld);

        document.getElementById('DistancePopup').classList.remove('hidden');
        document.getElementById('loadingOverlay').classList.remove('hidden');
        const canvas = document.getElementById('sceneCanvas');
        initScene(canvas, ld);
    }

    // Aggiorna miss distance
    function updateMissDistance(ld) {
        // Calculate kilometers from lunar distance
        const kmDistance = ld * 384400;

        const formattedKm = number_format(kmDistance, 2);
        const formattedLd = number_format(ld, 2);

        const missDistanceText = `${formattedKm} km (${formattedLd} LD)`;
        objectData.Asteroide["Distanza dalla Terra"] = missDistanceText;

        const missDistanceElement = document.getElementById('missDistanceDisplay');
        if (missDistanceElement) {
            missDistanceElement.textContent = missDistanceText;
        }
    }

    function number_format(number, decimals) {
        return parseFloat(number).toFixed(decimals).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    // È la funzione più importante. Inizializza tutta la scena.
    function initScene(canvas, ldValue) {
        /** Loading Manager **/
        // Gestisce il caricamento delle texture (utile per mostrare un overlay di "loading" finché non ha finito).
        loadingManager = new THREE.LoadingManager();
        loadingManager.onProgress = function(url, itemsLoaded, itemsTotal) {
            loadedItemCount++;
            const progress = Math.min(loadedItemCount / totalItemsToLoad, 1);
        };

        // Quando tutte sono caricate, si nasconde il loader grafico.
        loadingManager.onLoad = function() {
            document.getElementById('loadingOverlay').classList.add('hidden');
        };

        /** Setup della scena **/
        scene = new THREE.Scene();
        /* Valori PerspectiveCamera:
           1 - FOV (Field of View) in gradi. È l'ampiezza del campo visivo verticale. Più basso = più "zoomata".
           2 - Aspect ratio. È il rapporto larghezza/altezza della canvas. Serve a evitare che la scena venga distorta.
           3 - Near clipping plane: distanza minima alla quale un oggetto è visibile. Tutto ciò che è più vicino viene ignorato.
           4 - Far clipping plane: distanza massima alla quale un oggetto è visibile. Tutto ciò che è più lontano viene ignorato. (non usato)
        */
        camera = new THREE.PerspectiveCamera(60, canvas.clientWidth / canvas.clientHeight, 0.1);
        camera.position.set(10, 6, 13);     // x,y,z


        /** Renderer **/
        // Il renderer è l'oggetto che disegna la scena sullo schermo. Traduce la tua scena 3D (oggetti, camera, luci…) in immagini visibili nel browser, usando WebGL.
        renderer = new THREE.WebGLRenderer({ canvas, antialias: true });    // Attiva l'antialiasing, che serve a rendere le linee più lisce.
        // Imposta la dimensione del renderer per combaciare con quella della canvas, per evitare distorsioni
        renderer.setSize(canvas.clientWidth, canvas.clientHeight);
        // Colore di sfondo
        renderer.setClearColor(0x000000);
        renderer.shadowMap.enabled = true;
        // PCFSoftShadowMap crea ombre più morbide e realistiche, anche se più lento di altre opzioni.
        renderer.shadowMap.type = THREE.PCFSoftShadowMap;

        /**  OrbitControls **/
        // Permette all'utente di interagire con la scena (ruotare, zoomare).
        // camera = la telecamera da controllare; renderer.domElement = il canvas su cui applicare i controlli.
        controls = new OrbitControls(camera, renderer.domElement);

        // Damping = rende i movimenti più "fluidi" e realistici.
        controls.enableDamping = true;
        controls.dampingFactor = 0.05;  // più piccolo = più lento

        // Permette di trascinare la scena orizzontalmente e verticalmente (pan) nel piano dello schermo
        controls.screenSpacePanning = true;
        controls.zoomSpeed = 0.5;

        /** Raycasting **/
        // Serve per rilevare se l'utente clicca un oggetto 3D nella scena
        // Crea un "raggio virtuale" che parte dalla camera e può colpire oggetti 3D per identificarli.
        raycaster = new THREE.Raycaster();
        mouse = new THREE.Vector2();

        /** Scala e distanza **/
        const baseScaleFactor = 10; // Scala base fissa per Terra-Luna
        const moonDistance = baseScaleFactor; // Distanza Terra-Luna sempre fissa

        let asteroidDistance;
        if (ldValue <= 22) {
            asteroidDistance = ldValue * baseScaleFactor;
        } else {
            // Per LD > 22, usiamo una scala progressivamente ridotta per mantenere l'asteroide visibile ma spostato sufficientemente a destra
            asteroidDistance = 22 * baseScaleFactor + (ldValue - 22) * 5;
        }


        // Aggiunta delle stelle
        createStars();

        /** Illuminazione **/
            // Illuminazione ambientale - diffusa a tutta la scena
        const ambientLight = new THREE.AmbientLight(0x777777);
        scene.add(ambientLight);

        // Luce direzionale "solare" (come se fosse il Sole); il secondo valore indica l'intensità
        const sunLight = new THREE.DirectionalLight(0xffffff, 3);
        // Posizione da cui proviene la luce: un punto nello spazio (X, Y, Z).
        sunLight.position.set(10, 5, 5);
        // Attiva l'emissione di ombre da parte di questa luce.
        sunLight.castShadow = true;
        sunLight.shadow.mapSize.width = 2048;
        sunLight.shadow.mapSize.height = 2048;
        scene.add(sunLight);

        // Luce di riempimento (per evitare ombre troppo scure).
        const fillLight = new THREE.DirectionalLight(0x334466, 0.5);
        fillLight.position.set(-10, -5, -5);
        scene.add(fillLight);

        // Luce frontale per illuminare meglio la Terra
        const frontLight = new THREE.DirectionalLight(0xffffff, 1);
        frontLight.position.set(0, 0, 10);
        scene.add(frontLight);

        /** CREAZIONE SISTEMA DI ROTAZIONE TERRA-LUNA **/
        // Creiamo un nuovo gruppo per il sistema Terra-Luna
        earthRotationSystem = new THREE.Group();
        scene.add(earthRotationSystem);

        // Terra (gruppo)
        earthGroup = new THREE.Group();
        // La Terra è il centro del sistema, posta a (0,0,0).
        earthGroup.position.set(0, 0, 0);
        earthGroup.userData = { name: "Terra" };
        earthRotationSystem.add(earthGroup);

        // Luna (sistema di rotazione attorno la Terra)
        moonRotationSystem = new THREE.Group();
        earthRotationSystem.add(moonRotationSystem);

        // Luna (gruppo)
        moonGroup = new THREE.Group();
        moonGroup.position.set(moonDistance, 0, 0);
        moonGroup.userData = { name: "Luna" };
        moonRotationSystem.add(moonGroup);

        // Asteroide (gruppo)
        asteroidGroup = new THREE.Group();
        asteroidGroup.position.set(asteroidDistance, 0, 0);
        asteroidGroup.userData = { name: "Asteroide" };
        scene.add(asteroidGroup);


        /** Caricamento delle texture **/
        const textureLoader = new THREE.TextureLoader(loadingManager);
        // TERRA
        const earthTexture = textureLoader.load(texturePaths.earth.map);
        // LUNA
        const moonTexture = textureLoader.load(texturePaths.moon.map);
        // ASTEROIDE
        const asteroidTexture = textureLoader.load(texturePaths.asteroid.map);


        /** Creazione Terra **/
        earth = new THREE.Mesh(
            // Crea una mesh sferica per la Terra: raggio 2 e 64 suddivisioni per avere sfera liscia
            new THREE.SphereGeometry(2, 64, 64),
            new THREE.MeshPhongMaterial({
                map: earthTexture,
                specular: new THREE.Color(0x333333),
                shininess: 5
            })
        );
        // La Terra riceve e proietta ombre.
        earth.receiveShadow = true;
        earth.castShadow = true;
        earthGroup.add(earth);

        // Atmosfera terrestre
        const earthGlowGeometry = new THREE.SphereGeometry(2.2, 64, 64);
        const earthGlowMaterial = new THREE.MeshBasicMaterial({
            color: 0x009cdf,
            transparent: true,
            opacity: 0.1,
            side: THREE.BackSide
        });
        earthGlow = new THREE.Mesh(earthGlowGeometry, earthGlowMaterial);
        earthGroup.add(earthGlow);


        /** Creazione Luna **/
        moon = new THREE.Mesh(
            new THREE.SphereGeometry(0.5, 64, 64),
            new THREE.MeshPhongMaterial({
                map: moonTexture,
                shininess: 0
            })
        );
        moon.receiveShadow = true;
        moon.castShadow = true;
        moonGroup.add(moon);

        // Sottile bagliore lunare
        const moonGlowGeometry = new THREE.SphereGeometry(0.57, 32, 32);
        const moonGlowMaterial = new THREE.MeshBasicMaterial({
            color: 0xaaaaff,
            transparent: true,
            opacity: 0.1,
            side: THREE.BackSide
        });
        moonGlow = new THREE.Mesh(moonGlowGeometry, moonGlowMaterial);
        moonGroup.add(moonGlow);

        /** Creazione Asteroide **/
        createAsteroid(asteroidTexture);

        /* Linea Terra-Luna
        const line = new THREE.Line(
            new THREE.BufferGeometry().setFromPoints([
                new THREE.Vector3(0, 0, 0),
                new THREE.Vector3(moonDistance, 0, 0)
            ]),
            new THREE.LineBasicMaterial({ color: 0x4477ff, opacity: 0.3, transparent: true })
        );
        earthRotationSystem.add(line);*/

        /** Linea Terra-Asteroide **/
        const astLine = new THREE.Line(
            new THREE.BufferGeometry().setFromPoints([
                new THREE.Vector3(0, 0, 0),
                new THREE.Vector3(asteroidDistance, 0, 0)
            ]),
            new THREE.LineDashedMaterial({
                color: 0xff3333,
                dashSize: 0.5,
                gapSize: 0.3,
                opacity: 0.5,
                transparent: true
            })
        );
        astLine.computeLineDistances();
        scene.add(astLine);

        /** Orbita lunare (cerchio) **/
        const orbitGeometry = new THREE.BufferGeometry().setFromPoints(
            // absarc(x, y, raggio, angoloStart, angoloEnd, sensoOrario) crea un arco di cerchio (qui un cerchio completo).
            // moonDistance: distanza Terra-Luna in LD (Lunar Distance), il raggio dell'orbita.
            // Math.PI * 2: indica un cerchio completo (360° in radianti).
            // getPoints(128): campiona il cerchio in 128 punti per ottenere una curva liscia.
            new THREE.Path().absarc(0, 0, moonDistance, 0, Math.PI * 2, true).getPoints(128)
        );
        // Crea una linea orbitale
        const orbitLine = new THREE.Line(
            orbitGeometry,
            new THREE.LineBasicMaterial({ color: 0x4477ff, opacity: 0.3, transparent: true })
        );
        // Ruota la linea per posizionarla sul piano XZ, come un'orbita reale intorno alla Terra vista dall'alto.
        orbitLine.rotation.x = Math.PI / 2;
        earthRotationSystem.add(orbitLine);

        // Evento di resize
        window.addEventListener('resize', onWindowResize);

        // Evento di click
        canvas.addEventListener('click', onClick);

        // Aggiorna il rendering
        animate();
    }

    /** Asteroide **/
    function createAsteroid(texture) {
        // Creiamo una geometria irregolare per l'asteroide
        // Il primo valore indica le dimensioni ed il secondo indica il grado di irregolarità
        const geometry = new THREE.IcosahedronGeometry(0.3, 1);

        // Creazione del materiale dell'asteroide - Migliorato per essere più visibile
        const material = new THREE.MeshStandardMaterial({
            map: texture,
            roughness: 0.7,     // superficie abbastanza ruvida.
            metalness: 0.3,     // effetto metallico moderato.
            emissive: 0x333333, // colore emesso anche senza luce
        });

        // Creazione della mesh
        asteroid = new THREE.Mesh(geometry, material);
        asteroid.receiveShadow = true;
        asteroid.castShadow = true;

        // Rotazione iniziale casuale
        asteroid.rotation.x = Math.random() * Math.PI;
        asteroid.rotation.y = Math.random() * Math.PI;
        asteroid.rotation.z = Math.random() * Math.PI;

        // Aggiungiamo l'asteroide al gruppo
        asteroidGroup.add(asteroid);

        // Aggiunta di una luce puntuale più forte sull'asteroide per evidenziarlo
        const asteroidLight = new THREE.PointLight(0xff9900, 1, 0.5);
        asteroidLight.position.set(0, 0, 0);
        asteroidGroup.add(asteroidLight);

        // Aggiunta di un piccolo bagliore arancione attorno all'asteroide per renderlo più visibile
        const asteroidGlowGeometry = new THREE.SphereGeometry(0.35, 16, 16);
        const asteroidGlowMaterial = new THREE.MeshBasicMaterial({
            color: 0xff5500,
            transparent: true,
            opacity: 0.1,
            side: THREE.BackSide
        });
        const asteroidGlow = new THREE.Mesh(asteroidGlowGeometry, asteroidGlowMaterial);
        asteroidGroup.add(asteroidGlow);
    }

    /** Stelle **/
    function createStars() {
        // Creiamo un cielo stellato con diverse profondità per effetto parallasse
        const starsGeometry = [
            new THREE.BufferGeometry(),
            new THREE.BufferGeometry()
        ];


        const starsMaterials = [
            // Stelle bianche vicine (grandi e luminose).
            new THREE.PointsMaterial({
                color: 0xffffff,
                size: 0.7,
                opacity: 2,
                transparent: true,
                sizeAttenuation: false
            }),

            // Stelle azzurrine lontane (più piccole e meno intense).
            new THREE.PointsMaterial({
                color: 0xaaaaff,
                size: 0.5,
                opacity: 0.6,
                transparent: true,
                sizeAttenuation: false
            })
        ];

        let closestStars = 6000;    // numero di stelle
        const starsVertices1 = [];
        for (let i = 0; i < closestStars; i++) {
            const star = new THREE.Vector3();
            star.x = THREE.MathUtils.randFloatSpread(2000);
            star.y = THREE.MathUtils.randFloatSpread(2000);
            star.z = THREE.MathUtils.randFloatSpread(2000);
            starsVertices1.push(star.x, star.y, star.z);
        }

        let furthestStars = 4000;
        const starsVertices2 = [];
        for (let i = 0; i < furthestStars; i++) {
            const star = new THREE.Vector3();
            star.x = THREE.MathUtils.randFloatSpread(2000);
            star.y = THREE.MathUtils.randFloatSpread(2000);
            star.z = THREE.MathUtils.randFloatSpread(2000);
            starsVertices2.push(star.x, star.y, star.z);
        }

        starsGeometry[0].setAttribute('position', new THREE.Float32BufferAttribute(starsVertices1, 3));
        starsGeometry[1].setAttribute('position', new THREE.Float32BufferAttribute(starsVertices2, 3));

        const starField1 = new THREE.Points(starsGeometry[0], starsMaterials[0]);
        const starField2 = new THREE.Points(starsGeometry[1], starsMaterials[1]);

        scene.add(starField1);
        scene.add(starField2);
    }

    function onWindowResize() {
        const canvas = renderer.domElement;
        // aggiorna il rapporto d'aspetto della camera (larghezza/altezza), necessario per evitare distorsioni visive.
        camera.aspect = canvas.clientWidth / canvas.clientHeight;
        // rigenera la matrice di proiezione della camera in base al nuovo rapporto.
        camera.updateProjectionMatrix();
        // ridimensiona la finestra di rendering per adattarsi al nuovo canvas.
        renderer.setSize(canvas.clientWidth, canvas.clientHeight);
    }

    function onClick(event) {
        // Calcola posizione del mouse normalizzata (-1 to +1)
        const rect = renderer.domElement.getBoundingClientRect();
        // coordinate del mouse nel browser.
        mouse.x = ((event.clientX - rect.left) / rect.width) * 2 - 1;
        mouse.y = -((event.clientY - rect.top) / rect.height) * 2 + 1;

        // Esegui il raycasting - crea un raggio virtuale dalla posizione del mouse alla scena 3D.
        raycaster.setFromCamera(mouse, camera);
        const intersects = raycaster.intersectObjects([earth, moon, asteroid]); // restituisce un array di oggetti colpiti dal raggio

        if (intersects.length > 0) {
            let obj = intersects[0].object;

            // Trova il gruppo genitore
            let group;
            if (obj === earth || obj === earthClouds) {
                group = earthGroup;
            } else if (obj === moon || obj === moonGlow) {
                group = moonGroup;
                // Attiviamo il tracking per la Luna
                followingObject = true;
                objectToFollow = moonGroup;
            } else if (obj === asteroid) {
                group = asteroidGroup;
            }

            if (group) {
                // Seleziona l'oggetto
                selectedObject = group;

                // Aggiorna le informazioni nel pannello
                updateInfoPanel(selectedObject);

                // Anima la camera per centrare l'oggetto
                const targetPosition = selectedObject.position.clone();

                // Nuova variabile per memorizzare la distanza desiderata per l'oggetto
                let desiredDistance;

                // Adatta la distanza in base all'oggetto selezionato
                if (selectedObject === earthGroup) {
                    desiredDistance = 5;
                    followingObject = false; // Non seguiamo la Terra
                }
                else if (selectedObject === moonGroup) {
                    desiredDistance = 3;
                    followingObject = true;  // Seguiamo la Luna
                    objectToFollow = moonGroup;
                    // Memorizza la distanza desiderata come proprietà dell'oggetto
                    objectToFollow.userData.desiredDistance = desiredDistance;
                }
                else if (selectedObject === asteroidGroup) {
                    desiredDistance = 4;
                    followingObject = false; // Non seguiamo l'asteroide
                }

                // Calcola nuova posizione della camera
                const direction = camera.position.clone().sub(controls.target).normalize();
                const newPosition = targetPosition.clone().add(direction.multiplyScalar(desiredDistance));

                // Anima lo spostamento
                animateCamera(newPosition, targetPosition, 1000);
            }
        } else {
            // Se clicchiamo nel vuoto, disattiviamo il tracking
            followingObject = false;
            objectToFollow = null;
        }
    }

    function updateInfoPanel(object) {
        const objectName = document.getElementById('objectName');
        const objectInfo = document.getElementById('objectInfo');

        const name = object.userData.name;
        objectName.textContent = name;

        let infoHTML = '';
        const data = objectData[name];

        for (const [key, value] of Object.entries(data)) {
            const displayValue = typeof value === 'function' ? value() : value;
            infoHTML += `<div class="mb-2">
                <div class="font-semibold">${key}:</div>
                <div>${displayValue}</div>
            </div>`;
        }

        objectInfo.innerHTML = infoHTML;
    }

    // Anima la transizione fluida della camera verso l'oggetto selezionato.
    function animateCamera(newPosition, newTarget, duration) {
        const startPosition = camera.position.clone();
        const startTarget = controls.target.clone();
        const startTime = Date.now();

        function updateCamera() {
            const elapsed = Date.now() - startTime;
            const progress = elapsed / duration;

            if (progress >= 1) {
                camera.position.copy(newPosition);
                controls.target.copy(newTarget);
                controls.update();
                return;
            }

            //  funzione di easing per rendere il movimento più naturale (accelera, poi rallenta).
            const t = easeInOutQuad(progress);
            // Interpolazione lineare tra la posizione iniziale e quella finale
            // Sposta la camera dal punto A (startPosition) al punto B (newPosition) in base a una percentuale t.
            camera.position.lerpVectors(startPosition, newPosition, t);
            controls.target.lerpVectors(startTarget, newTarget, t);
            controls.update();

            requestAnimationFrame(updateCamera);
        }

        // Se non stiamo seguendo un oggetto, eseguiamo l'animazione normale
        if (!followingObject) {
            updateCamera();
        }
    }

    // Funzione di easing per animazioni più naturali
    function easeInOutQuad(t) {
        return t < 0.5 ? 2 * t * t : 1 - Math.pow(-2 * t + 2, 2) / 2;
    }

    function animate() {
        animationId = requestAnimationFrame(animate);

        // Rotazione lenta degli oggetti
        if (earth) {
            earth.rotation.y += 0.001; // Rotazione della Terra su se stessa
        }

        if (earthClouds) {
            earthClouds.rotation.y += 0.0015; // Le nuvole si muovono un po' più veloci
        }

        if (asteroid) {
            asteroid.rotation.x += 0.001;
            asteroid.rotation.y += 0.002;
        }

        // Rivoluzione Luna attorno alla Terra
        if (moonRotationSystem) {
            moonRotationSystem.rotation.y += 0.003;
        }

        // Rotazione sincrona della Luna
        if (moon) {
            // La Luna mostra sempre la stessa faccia alla Terra (rivoluzione = rotazione)
            //moon.rotation.y += 0.003; // Compensa la rotazione orbitale
            moon.rotation.x += 0.003; // Compensa la rotazione orbitale
        }

        // Rotazione lenta dell'intero sistema Terra-Luna
        if (earthRotationSystem) {
            earthRotationSystem.rotation.y += 0.00005; // Rotazione molto lenta del sistema
        }

        // Seguiamo la Luna
        if (followingObject && objectToFollow) {
            // Otteniamo la posizione mondiale dell'oggetto da seguire
            const worldPosition = new THREE.Vector3();
            objectToFollow.getWorldPosition(worldPosition);

            // Aggiorniamo il target dei controlli alla nuova posizione dell'oggetto
            controls.target.copy(worldPosition);

            // Utilizziamo la distanza desiderata salvata nell'oggetto
            const desiredDistance = objectToFollow.userData.desiredDistance || 3;

            // Manteniamo la direzione ma impostiamo la distanza desiderata
            const offset = camera.position.clone().sub(controls.target);
            offset.normalize();

            // Calcoliamo la nuova posizione della camera con la distanza desiderata
            camera.position.copy(worldPosition).add(offset.multiplyScalar(desiredDistance));
        }

        controls.update();
        renderer.render(scene, camera);
    }

    window.closeDistancePopup = function() {
        document.getElementById('DistancePopup').classList.add('hidden');
        if (animationId) cancelAnimationFrame(animationId);
        if (renderer) {
            renderer.dispose();
            renderer.forceContextLoss();
            renderer.domElement = null;
        }
        window.removeEventListener('resize', onWindowResize);
    }
</script>
