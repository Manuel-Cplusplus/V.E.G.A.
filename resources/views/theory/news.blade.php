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
            <h1 class="text-2xl font-bold text-gray-800 mb-4">News</h1>

            <div id="content-area" class="border-blue-900 border-2 shadow-lg shadow-black rounded-lg p-6">
                <div class="flex flex-row items-center text-center">
                    <img src="{{ asset('media/logo/uniba-logo.png') }}" alt="Logo Università degli studi di Bari" class="w-14 h-14 mr-5 cursor-pointer flex hover:scale-110 transition-transform duration-200">
                    <h1 class="text-2xl font-bold text-gray-800 mb-1 underline">Scoperto dall'Università di Bari uno dei meteoriti più rari del mondo</h1>
                </div>
                <p class="text-gray-600 ml-20 -mt-2">Si tratta di un meteorite minuscolo eppure fondamentale perché al suo interno contiene un materiale ritenuto quasi impossibile perché viola le regole finora conosciute.</p>
                <a href = "https://www.lagazzettadelmezzogiorno.it/news/bari/1475238/scoperto-dall-universita-di-bari-uno-dei-meteoriti-piu-rari-del-mondo.html" target="_blank" class="ml-20 text-blue-500 hover:underline">Leggi la Notizia</a>
            </div>
        </div>
    </section>
</main>

</body>
</html>

<script>
    document.getElementById('menu-news')?.setAttribute('data-selected', 'true');
</script>

