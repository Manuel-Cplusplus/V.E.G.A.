<!-- Loader Uiverse - Nervous Panda -->
<!-- https://uiverse.io/BlackisPlay/nervous-panda-86 -->

<!-- Loading screen -->
<div id="loadingOverlay"
     class="fixed top-0 left-0 w-full h-full bg-gray-900 bg-opacity-50 items-center justify-center z-50 hidden">

    <!-- From Uiverse.io by BlackisPlay -->
    <div id="planetTrail1"></div>
    <div id="planetTrail2"></div>
    <div id="planetTrail3"></div>

    <div class="planets">
        <div id="planet"></div>
        <div id="star"></div>

        <div id="starShadow"></div>
        <div id="blackHoleDisk2"></div>
        <div id="blackHole"></div>
        <div id="blackHoleDisk1"></div>
    </div>

</div>


<style>
    /* From Uiverse.io by BlackisPlay */
    .planets {
        position: relative;
        height: 100px;
        width: 100px;
        display: flex;
    }

    #planetTrail1,
    #planetTrail2,
    #planetTrail3 {
        outline: solid rgb(255, 255, 255) 1px;
        border-radius: 50%;
        position: absolute;
    }

    #planetTrail1::after,
    #planetTrail2::after,
    #planetTrail3::after {
        content: "";
        width: 20px;
        height: 20px;
        position: absolute;
        border-radius: 50%;
        top: -5px;
        left: 50%;
        transform: translateX(-50%);
    }

    #planetTrail1::after {
        background-color: rgb(227, 227, 0);
    }

    #planetTrail2::after {
        background-color: rgb(0, 130, 255);
    }

    #planetTrail3::after {
        background-color: rgb(255, 0, 0);
    }

    #planetTrail1 {
        width: 120px;
        height: 120px;
        animation: trails1 4s infinite;
    }

    #planetTrail2 {
        width: 170px;
        height: 170px;
        animation: trails2 4s infinite;
    }

    #planetTrail3 {
        width: 220px;
        height: 220px;
        animation: trails3 4s infinite;
    }

    @keyframes trails1 {
        0% {
            transform: rotate(0deg);
        }
        40% {
            transform: rotate(360deg);
            width: 120px;
            height: 120px;
        }
        50% {
            width: 0px;
            height: 0px;
        }
        90% {
            width: 0px;
            height: 0px;
        }
        100% {
            width: 120px;
            height: 120px;
        }
    }

    @keyframes trails2 {
        0% {
            transform: rotate(0deg);
        }
        40% {
            transform: rotate(250deg);
            width: 170px;
            height: 170px;
        }
        50% {
            width: 0px;
            height: 0px;
        }
        90% {
            width: 0px;
            height: 0px;
        }
        100% {
            width: 170px;
            height: 170px;
        }
    }

    @keyframes trails3 {
        0% {
            transform: rotate(0deg);
        }
        40% {
            transform: rotate(170deg);
            width: 220px;
            height: 220px;
        }
        50% {
            width: 0px;
            height: 0px;
        }
        90% {
            width: 0px;
            height: 0px;
        }
        100% {
            width: 220px;
            height: 220px;
        }
    }

    #star {
        position: absolute;
        width: 50px;
        height: 50px;
        background-color: rgb(255, 170, 0);
        border-radius: 50%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        animation: bouncingStar 4s infinite;
    }

    #starShadow {
        position: absolute;
        width: 50px;
        height: 20px;
        background-color: rgb(197, 78, 36);
        border-radius: 50%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, 100%);
        filter: blur(5px);
        opacity: 0.3;
        animation: shadowAnimation 4s infinite;
    }

    @keyframes bouncingStar {
        0% {
            transform: translate(-50%, -50%);
        }
        10% {
            transform: translate(-50%, -30%);
        }
        20% {
            transform: translate(-50%, -50%);
        }
        30% {
            transform: translate(-50%, -30%);
        }
        40% {
            transform: translate(-50%, -50%);
            width: 50px;
            height: 50px;
        }
        50% {
            width: 0px;
            height: 0px;
        }
        90% {
            width: 0px;
            height: 0px;
        }
        100% {
            width: 50px;
            height: 50px;
        }
    }

    @keyframes shadowAnimation {
        0% {
            opacity: 0.1;
        }
        10% {
            opacity: 0.4;
        }
        20% {
            opacity: 0.1;
        }
        30% {
            opacity: 0.4;
        }
        40% {
            opacity: 0.1;
        }
        50% {
            opacity: 0;
        }
        90% {
            opacity: 0;
        }
        100% {
            opacity: 0.1;
        }
    }

    #blackHole {
        position: absolute;
        width: 50px;
        height: 50px;
        background-color: rgb(0, 0, 0);
        outline: orange solid 5px;
        border-radius: 50%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        animation: bouncingBlackHole 4s infinite;
    }

    @keyframes bouncingBlackHole {
        0% {
            height: 0px;
            width: 0px;
        }
        40% {
            width: 0px;
            height: 0px;
        }
        50% {
            width: 50px;
            height: 50px;
        }
        90% {
            width: 50px;
            height: 50px;
        }
        100% {
            width: 0px;
            height: 0px;
        }
    }

    #blackHoleDisk1 {
        position: absolute;
        width: 68px;
        height: 68px;
        clip-path: inset(50% 0 0 0);
        border: black 10px solid;
        border-radius: 50%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotateX(70deg);
        animation: diskAn 4s infinite;
    }

    #blackHoleDisk2 {
        position: absolute;
        width: 70px;
        height: 70px;
        clip-path: inset(0 0 50% 0);
        border: rgb(245, 174, 8) 10px solid;
        border-radius: 50%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotateX(55deg);
        animation: diskAn 4s infinite;
    }

    @keyframes diskAn {
        0% {
            height: 0px;
            width: 0px;
            border: orange 0px solid;
        }
        40% {
            width: 0px;
            height: 0px;
            border: orange 0px solid;
        }
        50% {
            width: 88px;
            height: 88px;
            border: orange 18px solid;
        }
        90% {
            width: 88px;
            height: 88px;
            border: orange 18px solid;
        }
        100% {
            width: 0px;
            height: 0px;
            border: orange 0px solid;
        }
    }

    #planet {
        position: absolute;
        width: 20px;
        height: 20px;
        background-color: rgb(255, 255, 255);
        border-radius: 50%;
        animation: planetAn 4s infinite;
    }

    @keyframes planetAn {
        0% {
            opacity: 0;
            transform: translate(0px, 0px);
            z-index: 1;
        }
        50% {
            opacity: 0;
            transform: translate(0px, 0px);
            z-index: 1;
        }
        58% {
            opacity: 1;
        }
        70% {
            opacity: 1;
            transform: translate(100px, 40px);
            z-index: 1;
        }
        71% {
            z-index: 0;
        }
        90% {
            z-index: 0;
            opacity: 1;
            transform: translate(-10px, 70px);
        }
        100% {
            transform: translate(-10px, 70px);
            opacity: 0;
        }
    }
</style>


<script>
    // Validazione e invio del form
    formConLoader.addEventListener('submit', function (event) {
        event.preventDefault(); // Evita l'invio automatico del form


        // Mostra il loading overlay
        document.getElementById('loadingOverlay').classList.add('flex');
        document.getElementById('loadingOverlay').classList.remove('hidden');

        // Invia il form
        this.submit();
    });
</script>
