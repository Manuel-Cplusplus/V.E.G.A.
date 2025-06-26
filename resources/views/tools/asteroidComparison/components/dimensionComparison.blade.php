{{--
    V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
    Copyright (c) 2025 Manuel Carlucci

    Licensed under CC BY-NC-SA 4.0: http://creativecommons.org/licenses/by-nc-sa/4.0/
--}}

<!-- Modal per confronto dimensioni -->
<div id="dimensionChart" class="fixed inset-0 flex items-center justify-center z-50 hidden">
    <div class="absolute inset-0 bg-black opacity-75"></div>

    <div class="relative bg-gray-900 rounded-xl shadow-lg max-w-6xl w-full mx-4 max-h-[90vh] flex flex-col">
        <!-- Header con titolo e pulsante di chiusura -->
        <div class="flex justify-between items-center p-4 border-b border-gray-700">
            <h3 class="text-xl font-semibold text-white">Confronto Dimensioni Asteroidi</h3>
            <button onclick="closeDimensionChart()" class="text-gray-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Contenuto del grafico-->
        <div class="p-4 flex-grow">
            <div id="asteroidVisualization" class="w-full h-[70vh]"></div>
        </div>

        <!-- Footer con legenda -->
        <div class="p-4 border-t border-gray-700">
            <div class="flex flex-wrap gap-4 justify-center">
                <div class="flex items-center">
                    <span class="inline-block w-4 h-4 rounded-full bg-red-500 mr-2"></span>
                    <span class="text-white text-sm">Asteroidi Pericolosi</span>
                </div>
                <div class="flex items-center">
                    <span class="inline-block w-4 h-4 rounded-full bg-blue-500 mr-2"></span>
                    <span class="text-white text-sm">Asteroidi Non Pericolosi</span>
                </div>
                <div class="flex items-center">
                    <span class="inline-block w-4 h-4 rounded-full bg-yellow-400 mr-2"></span>
                    <span class="text-white text-sm">Asteroidi con Rischio di Impatto</span>
                </div>
                <div class="flex items-center ml-6">
                    <span class="text-white text-sm italic">Gli asteroidi sono ordinati dal più piccolo (sinistra) al più grande (destra)</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script>
    // Funzioni per aprire e chiudere il modal
    function openDimensionChart() {
        document.getElementById('dimensionChart').classList.remove('hidden');
        initAsteroidVisualization();
    }

    function closeDimensionChart() {
        document.getElementById('dimensionChart').classList.add('hidden');
        // Pulizia delle risorse Three.js
        if (window.asteroidVisualizationRenderer) {
            window.asteroidVisualizationRenderer.dispose();
        }
    }

    // Funzione per inizializzare la visualizzazione degli asteroidi
    function initAsteroidVisualization() {
        // Recupera i dati degli asteroidi dal localStorage
        const detailedData = JSON.parse(localStorage.getItem('detailedAsteroidData')) || {};

        // Elemento che conterrà la visualizzazione
        const container = document.getElementById('asteroidVisualization');

        // Preparazione della scena Three.js
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(45, container.clientWidth / container.clientHeight, 0.1, 10000);

        const renderer = new THREE.WebGLRenderer({ antialias: true });
        renderer.setSize(container.clientWidth, container.clientHeight);
        renderer.setClearColor(0x000000);
        container.appendChild(renderer.domElement);

        // Salviamo il renderer per la pulizia
        window.asteroidVisualizationRenderer = renderer;

        // Aggiungiamo le luci
        const ambientLight = new THREE.AmbientLight(0x404040);
        scene.add(ambientLight);

        const directionalLight = new THREE.DirectionalLight(0xffffff, 1);
        directionalLight.position.set(1, 2, 1);
        scene.add(directionalLight);

        const directionalLeftLight = new THREE.DirectionalLight(0xffffff, 1);
        directionalLeftLight.position.set(-1, 2, 3);
        scene.add(directionalLeftLight);

        // Percorsi delle texture
        const texturePaths = {
            asteroid: {
                map: '/media/textures/asteroid/makemake_fictional.jpg',
            }
        };

        // Loading Manager per monitorare il caricamento delle texture
        const loadingManager = new THREE.LoadingManager();

        // Texture Loader
        const textureLoader = new THREE.TextureLoader(loadingManager);

        // Carica la texture dell'asteroide
        const asteroidTexture = textureLoader.load(texturePaths.asteroid.map);

        // Creazione delle stelle di sfondo
        createStars();

        // Prepariamo gli asteroidi
        const asteroids = [];
        const asteroidData = [];

        // Troviamo il diametro massimo per la scala
        let maxDiameter = 0;
        Object.values(detailedData).forEach(entry => {
            const asteroid = entry.asteroidData || {};
            if (asteroid.diameter && asteroid.diameter > maxDiameter) {
                maxDiameter = asteroid.diameter;
            }
        });

        // Scala per rendere visibili anche gli asteroidi più piccoli
        // Un asteroide con il diametro massimo avrà un raggio di 2 unità
        const scaleFactor = 4 / maxDiameter;

        // Posizione iniziale
        let xPosition = -((Object.keys(detailedData).length - 1) * 5) / 2;

        // Crea un array ordinato degli asteroidi dal più piccolo al più grande
        const sortedAsteroids = Object.values(detailedData)
            .filter(entry => (entry.asteroidData || {}).diameter) // Filtra solo quelli con un diametro
            .sort((a, b) => {
                const diameterA = a.asteroidData.diameter || 0;
                const diameterB = b.asteroidData.diameter || 0;
                return diameterA - diameterB; // Ordina dal più piccolo al più grande
            });

        // Crea gli asteroidi
        sortedAsteroids.forEach(entry => {
            const asteroid = entry.asteroidData || {};
            if (!asteroid.diameter) return;

            // Scala il diametro
            const radius = (asteroid.diameter * scaleFactor) / 2;

            // Determina il colore in base alle proprietà
            let color;
            if (asteroid.is_sentry_object) {
                color = 0xf0d000; // Giallo per gli asteroidi a rischio impatto
            } else if (asteroid.is_hazardous) {
                color = 0xff3b3b; // Rosso per gli asteroidi pericolosi
            } else {
                color = 0x3b82f6; // Blu per gli asteroidi non pericolosi
            }

            // Crea la geometria come un icosaedro invece che una sfera per renderlo più realistico
            const geometry = new THREE.IcosahedronGeometry(radius, 1);

            // Usa texture per renderlo più realistico
            const material = new THREE.MeshStandardMaterial({
                map: asteroidTexture,
                color: color,
                roughness: 0.7,     // superficie abbastanza ruvida
                metalness: 0.3,     // effetto metallico moderato
                emissive: 0x333333, // colore emesso anche senza luce
            });

            // Crea la mesh dell'asteroide
            const mesh = new THREE.Mesh(geometry, material);
            mesh.position.set(xPosition, 0, 0);
            scene.add(mesh);

            // Aggiungi etichetta con il nome
            addLabel(scene, asteroid.name || asteroid.designation, xPosition, radius * 1.5, 0);

            // Aggiungi linea di riferimento dal centro alla base
            addReferenceLine(scene, xPosition, -3, 0, xPosition, -radius, 0);

            // Aggiungi scala di riferimento
            addScaleLabel(scene, asteroid.diameter + " m", xPosition, -3.5, 0);

            asteroids.push(mesh);
            asteroidData.push({
                name: asteroid.name || asteroid.designation,
                diameter: asteroid.diameter,
                is_hazardous: asteroid.is_hazardous,
                is_sentry_object: asteroid.is_sentry_object
            });

            // Aggiorna la posizione per il prossimo asteroide
            xPosition += 5;
        });

        // Imposta la posizione della camera
        camera.position.set(0, 1, 15);
        camera.lookAt(0, 0, 0);

        // Variabili per il controllo della camera
        let isDragging = false;
        let previousMousePosition = {
            x: 0,
            y: 0
        };
        let cameraTarget = new THREE.Vector3(0, 0, 0);

        // Aggiungi controlli per lo zoom con la rotellina del mouse
        let zoomLevel = 15; // Posizione iniziale della camera
        const minZoom = 5;  // Zoom massimo (più vicino)
        const maxZoom = 30; // Zoom minimo (più lontano)

        // Gestione dello zoom con la rotellina del mouse
        container.addEventListener('wheel', (event) => {
            event.preventDefault();

            // Calcola il nuovo livello di zoom
            zoomLevel += event.deltaY * 0.01;

            // Limita il livello di zoom
            zoomLevel = Math.max(minZoom, Math.min(maxZoom, zoomLevel));

            // Applica il nuovo zoom
            camera.position.z = zoomLevel;
            camera.updateProjectionMatrix();
        });

        // Gestione del trascinamento per spostare la vista
        container.addEventListener('mousedown', (event) => {
            isDragging = true;
            previousMousePosition = {
                x: event.clientX,
                y: event.clientY
            };
        });

        document.addEventListener('mousemove', (event) => {
            if (!isDragging) return;

            const deltaMove = {
                x: event.clientX - previousMousePosition.x,
                y: event.clientY - previousMousePosition.y
            };

            // Velocità di trascinamento
            const dragSpeed = 0.01;

            // Aggiorna la posizione della camera
            cameraTarget.x -= deltaMove.x * dragSpeed * camera.position.z / 15;
            cameraTarget.y += deltaMove.y * dragSpeed * camera.position.z / 15;

            // Limita lo spostamento orizzontale
            const maxHorizontalOffset = 15;
            cameraTarget.x = Math.max(-maxHorizontalOffset, Math.min(maxHorizontalOffset, cameraTarget.x));

            // Limita lo spostamento verticale
            const maxVerticalOffset = 5;
            cameraTarget.y = Math.max(-maxVerticalOffset, Math.min(maxVerticalOffset, cameraTarget.y));

            // Aggiorna lo sguardo della camera
            camera.lookAt(cameraTarget);

            previousMousePosition = {
                x: event.clientX,
                y: event.clientY
            };
        });

        document.addEventListener('mouseup', () => {
            isDragging = false;
        });

        // Istruzioni per la navigazione con posizionamento migliorato
        const navigationInstructions = document.createElement('div');
        navigationInstructions.innerHTML = 'Usa la rotellina del mouse per zoomare<br>Trascina per muoverti nella scena';
        navigationInstructions.style.position = 'absolute';
        navigationInstructions.style.bottom = '60px';
        navigationInstructions.style.color = 'black'
        navigationInstructions.style.fontSize = '14px';
        navigationInstructions.style.backgroundColor = 'rgba(255, 255, 255, 0.5)'; // Sfondo bianco con opacità 50%
        navigationInstructions.style.padding = '8px 12px';
        navigationInstructions.style.borderRadius = '5px';
        navigationInstructions.style.zIndex = '10'; // Assicura che sia sopra altri elementi
        container.appendChild(navigationInstructions);


        // Funzione di animazione
        function animate() {
            requestAnimationFrame(animate);

            // Ruota lentamente gli asteroidi
            asteroids.forEach(asteroid => {
                asteroid.rotation.y += 0.005;
                asteroid.rotation.x += 0.002;
            });

            renderer.render(scene, camera);
        }

        // Gestione del ridimensionamento della finestra
        window.addEventListener('resize', () => {
            const width = container.clientWidth;
            const height = container.clientHeight;

            camera.aspect = width / height;
            camera.updateProjectionMatrix();

            renderer.setSize(width, height);
        });

        // Avvia l'animazione
        animate();

        // Funzione per creare stelle di sfondo con effetto parallasse
        function createStars() {
            // Creiamo un cielo stellato con diverse profondità per effetto parallasse
            const starsGeometry = [
                new THREE.BufferGeometry(),
                new THREE.BufferGeometry()
            ];

            const starsMaterials = [
                // Stelle bianche vicine (grandi e luminose)
                new THREE.PointsMaterial({
                    color: 0xffffff,
                    size: 0.7,
                    opacity: 2,
                    transparent: true,
                    sizeAttenuation: false
                }),

                // Stelle azzurrine lontane (più piccole e meno intense)
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
    }

    // Funzione per aggiungere etichette
    function addLabel(scene, text, x, y, z) {
        // Crea un canvas per l'etichetta
        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');
        canvas.width = 512;
        canvas.height = 256;


        // Scrivi il testo
        context.font = 'Bold 48px Arial';
        context.fillStyle = 'white';
        context.textAlign = 'center';
        context.fillText(text, canvas.width / 2, canvas.height / 2);

        // Crea una texture dal canvas
        const texture = new THREE.CanvasTexture(canvas);

        // Crea il materiale e la geometria
        const material = new THREE.SpriteMaterial({ map: texture, transparent: true });
        const sprite = new THREE.Sprite(material);

        // Posiziona l'etichetta
        sprite.position.set(x, y, z);
        sprite.scale.set(3, 1.5, 1);

        scene.add(sprite);
    }

    // Funzione per aggiungere linea di riferimento
    function addReferenceLine(scene, x1, y1, z1, x2, y2, z2) {
        const material = new THREE.LineBasicMaterial({ color: 0x444444 });
        const points = [];
        points.push(new THREE.Vector3(x1, y1, z1));
        points.push(new THREE.Vector3(x2, y2, z2));

        const geometry = new THREE.BufferGeometry().setFromPoints(points);
        const line = new THREE.Line(geometry, material);
        scene.add(line);
    }

    // Funzione per aggiungere etichetta di scala
    function addScaleLabel(scene, text, x, y, z) {
        // Crea un canvas per l'etichetta
        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');
        canvas.width = 512;
        canvas.height = 128;


        // Scrivi il testo
        context.font = 'Bold 40px Arial';
        context.fillStyle = 'rgba(255, 255, 255, 1)';
        context.textAlign = 'center';
        context.fillText(text, canvas.width / 2, canvas.height / 2);

        // Crea una texture dal canvas
        const texture = new THREE.CanvasTexture(canvas);

        // Crea il materiale e la geometria
        const material = new THREE.SpriteMaterial({ map: texture, transparent: true });
        const sprite = new THREE.Sprite(material);

        // Posiziona l'etichetta
        sprite.position.set(x, y, z);
        sprite.scale.set(2.5, 1, 1);

        scene.add(sprite);
    }
</script>
