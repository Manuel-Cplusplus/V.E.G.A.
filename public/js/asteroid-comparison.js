
/**
 * V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
 * Copyright (c) 2025 Manuel Carlucci
 *
 * This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/4.0/
 */


/**
 * Script per la comparazione delle dimensioni degli asteroidi
 */
function updateComparison(size) {
    size = parseInt(size);
    const guitarHeight = 1;
    const doorHeight = 2;
    const carLength = 4;
    const giraffeHeight = 5.5;
    const cristoRedentoreHeight = 38;
    const pisaTowerHeight = 57;
    const tajMahalHeight = 73;
    const libertyStatueHeight = 93;
    const footballFieldLength = 100;
    const gizaPyramidHeight = 146;
    const eiffelTowerHeight = 312;
    const empireStateBuildingHeight = 381;
    const BurjKhalifaHeight = 828;
    const MountEverestHeight = 8849;

    const comparisons = [
        { max: 1, name: `${(size / guitarHeight).toFixed(1)} Chitarra`, image: "chitarra.png", size: `${guitarHeight}m` },
        { max: 2, name: `${(size / doorHeight).toFixed(1)} Porta`, image: "door.png", size: `${doorHeight}m` },
        { max: 4, name: `${(size / carLength).toFixed(1)} Auto`, image: "car.png", size: `${carLength}m` },
        { max: 30, name: `${(size / giraffeHeight).toFixed(1)} Giraffe`, image: "giraffe.png", size: `${giraffeHeight}m` },
        { max: 50, name: `${(size / cristoRedentoreHeight).toFixed(1)} Cristo Redentore`, image: "cristo_redentore.png", size: `${cristoRedentoreHeight}m` },
        { max: 65, name: `${(size / pisaTowerHeight).toFixed(1)} Torre di Pisa`, image: "torre_di_pisa.png", size: `${pisaTowerHeight}m` },
        { max: 80, name: `${(size / tajMahalHeight).toFixed(1)} Taj Mahal`, image: "taj-mahal.png", size: `${tajMahalHeight}m` },
        { max: 90, name: `${(size / libertyStatueHeight).toFixed(1)} Statua della Libertà`, image: "liberty_statue.png", size: `${libertyStatueHeight}m` },
        { max: 130, name: `${(size / footballFieldLength).toFixed(1)} Campo Da Calcio`, image: "stadio.png", size: `${footballFieldLength}m` },
        { max: 300, name: `${(size / gizaPyramidHeight).toFixed(1)} Piramide di Giza`, image: "piramide.png", size: `${gizaPyramidHeight}m` },
        { max: 360, name: `${(size / eiffelTowerHeight).toFixed(1)} Torre Eiffel`, image: "eiffel-tower.png", size: `${eiffelTowerHeight}m` },
        { max: 750, name: `${(size / empireStateBuildingHeight).toFixed(1)} Empire State Building`, image: "empire_state_building.png", size: `${empireStateBuildingHeight}m` },
        { max: 800, name: `${(size / eiffelTowerHeight).toFixed(1)} Torre Eiffel`, image: "eiffel-tower.png", size: `${eiffelTowerHeight}m` },
        { max: 8000, name: `${(size / BurjKhalifaHeight).toFixed(1)} Burj Khalifa`, image: "burj_khalifa.png", size: `${BurjKhalifaHeight}m` },
        { max: Infinity, name: `${(size / MountEverestHeight).toFixed(1)} Everest`, image: "everest.png", size: `${MountEverestHeight}m` }
    ];

    const comparison = comparisons.find(c => size <= c.max) || comparisons[comparisons.length - 1];

    document.getElementById("comparison-name").innerText = comparison.name;
    document.getElementById("comparison-image").src = `/media/icons/comparisons/${comparison.image}`;
    document.getElementById("comparison-image").alt = comparison.name;
    document.getElementById("comparison-size").innerText = comparison.size;
}

// Funzione per inizializzare la comparazione
function initAsteroidComparison(asteroidSize) {
    document.addEventListener("DOMContentLoaded", function() {
        updateComparison(asteroidSize);
    });
}
