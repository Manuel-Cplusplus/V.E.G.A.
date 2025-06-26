{{--
    V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
    Copyright (c) 2025 Manuel Carlucci

    Licensed under CC BY-NC-SA 4.0: http://creativecommons.org/licenses/by-nc-sa/4.0/
--}}

<!-- Layout header -->
<header>
    <div class="relative h-20 bg-cover bg-center" style="background-image: url('{{ asset('media/images/header/header5.png') }}');">
        <!-- Overlay per migliore leggibilità -->
        <div class="absolute inset-0 bg-black opacity-10"></div>

        <div class="relative flex items-center justify-center">
            <!-- Logo -->
            <div class="mt-2 px-5">
                <img onclick = window.location.href='{{ route('homepage') }}' src="{{ asset('media/logo/logo.png') }}" alt="Logo" class="h-16 w-auto cursor-pointer">
            </div>

            <!-- Testo centrato -->
            <div class="mr-24 text-white text-center">
                <p class=" mr-5 text-2xl font-extrabold">V.E.G.A.</p>
                <p class="mr-5 text-sm">Visual Exploration and Graphical Analysis of Asteroids</p>
            </div>
        </div>



    </div>

    <!-- Navbar -->
    @include('layouts.navigation')
</header>
