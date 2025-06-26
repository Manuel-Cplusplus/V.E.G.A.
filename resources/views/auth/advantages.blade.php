
{{--
    V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
    Copyright (c) 2025 Manuel Carlucci

    Licensed under CC BY-NC-SA 4.0: http://creativecommons.org/licenses/by-nc-sa/4.0/
--}}


<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('layouts.head')

<body class="antialiased min-h-screen">
<!-- antialiased: Migliora la leggibilità del testo sullo schermo -->
<!-- min-h-screen: Imposta l'altezza minima del corpo (<body>) uguale all'altezza dello schermo (viewport). -->

@include('layouts.header')


<div class="flex justify-center mt-4">
    <main class="flex w-full max-w-5xl rounded-2xl shadow-lg shadow-black overflow-hidden bg-grey-100">

        <!-- Sezione sinistra: Tabella Caratteristiche -->
        <div class="w-2/3 px-8 py-2 bg-gray-100">
            <h2 class="text-2xl font-bold text-center italic mb-4">Caratteristiche</h2>
            <table class="w-full border-collapse border border-gray-300 text-center text-[14px]">
                <thead>
                <tr class="bg-gray-300">
                    <th class="p-2 border border-gray-400">Caratteristiche</th>
                    <th class="p-2 border border-gray-400">Ospite</th>
                    <th class="p-2 border border-gray-400">Utente Registrato</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="p-2 border border-gray-400">Limite di ricerche (*)
                        <p class = "text-[11px]">Limiti imposti da <a href="https://api.nasa.gov/" class="text-blue-500" target="_blank">https://api.nasa.gov/</a></p>
                    </td>
                    <td class="p-2 border border-gray-400">30/ora - 50/giorno</td>
                    <td class="p-2 border border-gray-400">1000/ora - 2000/ora</td>
                </tr>
                <tr>
                    <td class="p-2 border border-gray-400">Funzionalità Base
                        <p class = "text-[11px]">(Cerca Asteroide, Incontri Ravvicinati, Impatti Atmosferici, Possibili Impatti Futuri)</p>
                    </td>
                    <td class="p-2 border border-gray-400">✔</td>
                    <td class="p-2 border border-gray-400">✔</td>
                </tr>
                <tr>
                    <td class="p-2 border border-gray-400">Spiegazione dei dati tramite modelli di AI</td>
                    <td class="p-2 border border-gray-400">✔</td>
                    <td class="p-2 border border-gray-400">✔</td>
                </tr>
                <tr>
                    <td class="p-2 border border-gray-400">Monitoraggio Tempo Reale</td>
                    <td class="p-2 border border-gray-400">✖</td>
                    <td class="p-2 border border-gray-400">✔</td>
                </tr>
                <tr>
                    <td class="p-2 border border-gray-400">Accesso a dati tecnici</td>
                    <td class="p-2 border border-gray-400">✖</td>
                    <td class="p-2 border border-gray-400">✔</td>
                </tr>
                <tr>
                    <td class="p-2 border border-gray-400">Gestione dei Preferiti</td>
                    <td class="p-2 border border-gray-400">✖</td>
                    <td class="p-2 border border-gray-400">✔</td>
                </tr>
                <tr>
                    <td class="p-2 border border-gray-400">Confronto Asteroidi </td>
                    <td class="p-2 border border-gray-400">✖</td>
                    <td class="p-2 border border-gray-400">✔</td>
                </tr>
                <tr>
                    <td class="p-2 border border-gray-400">Creazione di Contenuti didattici </td>
                    <td class="p-2 border border-gray-400">✖</td>
                    <td class="p-2 border border-gray-400">✔</td>
                </tr>
                </tbody>
            </table>
            <p class = "mt-4 text-[12px] text-center"> (*) Ogni volta che chiedi un'informazione, il sistema invia una richiesta ai database NASA. </p>
        </div>

        <!-- Sezione destra: Login / Registrazione -->
        <div class="w-1/3 p-8 bg-gradient-to-b from-cyan-800 to-blue-500 text-white text-center flex flex-col justify-center shadow-[8px_0_15px_rgba(0,0,0,0.8),-8px_0_15px_rgba(0,0,0,0.8)]">
            <h2 class="text-lg font-semibold italic">Hai già un Account?</h2>
            <p class="mt-2">Accedi al nostro sistema per usufruire di vantaggi esclusivi.</p>
            <a href="{{ route('login') }}" class="block mt-4 bg-yellow-500 text-black py-2 rounded-md font-bold border-black border-2">Log In</a>

            <h2 class="text-lg font-semibold italic mt-8">Sei Nuovo?</h2>
            <p class="mt-2">Registrati e inizia questo viaggio con noi!</p>
            <a href="{{ route('register') }}" class="block mt-4 bg-yellow-500 text-black py-2 rounded-md font-bold border-black border-2">Registrati</a>
        </div>
    </main>
</div>

</body>

{{-- @include('layouts.footer') --}}
</html>


