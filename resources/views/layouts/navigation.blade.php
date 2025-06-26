{{--
    V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
    Copyright (c) 2025 Manuel Carlucci

    Licensed under CC BY-NC-SA 4.0: http://creativecommons.org/licenses/by-nc-sa/4.0/
--}}

<nav class="bg-[#0D101F] text-white flex justify-between items-center">
    <!-- Sinistra -->
    <div class="flex items-center space-x-6">
        <a href="{{ route('homepage') }}"
           class="text-medium ml-6 hover:bg-[#bef6] hover:rounded-md px-4 py-2 transition-all duration-200">Home</a>

        <!-- Descrizione Progetto -->
        <a href="{{route('info')}}"
           class="text-medium ml-6 hover:bg-[#bef6] hover:rounded-md px-4 py-2 transition-all duration-200">Il
            Progetto</a>

        <!-- Feedback -->
        <a href="https://forms.gle/77nUugUfDAzsgRXA8"
           class="text-medium ml-6 hover:bg-[#bef6] hover:rounded-md px-4 py-2 transition-all duration-200">Feedback</a>

        <!-- Tools -->
        @include('layouts.navComponents.tools')

        <!-- Sezione Teorica -->
        <a href="{{ route('theory.glossario') }}"
           class="text-medium ml-6 hover:bg-[#bef6] hover:rounded-md px-4 py-2 transition-all duration-200">Sezione Teorica</a>

        <!-- Sezione Learn -->
        <a href="{{ route('CreateLearn') }}"
           class="text-medium ml-6 hover:bg-[#bef6] hover:rounded-md px-4 py-2 transition-all duration-200">Impara con AI</a>

    </div>


    <!-- Destra -->
    <div class="relative group flex items-center">
        <!-- Notifica -->
        @include('layouts.navComponents.notification')

        <!-- Chatbot -->
        @include('layouts.navComponents.chatbot')

        <!-- Preferiti -->
        @include('layouts.navComponents.favorites')

        <!-- Menu Utente -->
        @include('layouts.navComponents.userMenu')

    </div>
</nav>

<!-- Popup Modal -->
@include('components.popUp.login-popUp')

