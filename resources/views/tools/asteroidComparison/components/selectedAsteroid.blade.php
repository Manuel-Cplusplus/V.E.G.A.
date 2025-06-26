{{--
    V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
    Copyright (c) 2025 Manuel Carlucci

    Licensed under CC BY-NC-SA 4.0: http://creativecommons.org/licenses/by-nc-sa/4.0/
--}}

<!-- Box Asteroide Selezionato -->
<div class="bg-[#0D101F] opacity-85 border border-white rounded-xl p-4 w-96 shadow-lg mt-1">
    <!-- Header con titolo e icone -->
    <div class="flex justify-between items-start mb-3">

        <!-- Icone con testo allineate a destra -->
        <div class="flex">
            @if(isset($asteroidData))
                <!-- Icona Rimuovi dal Confronto -->
                <div class="flex flex-col items-center">
                    <img src="{{ asset('media/icons/remove.png') }}" alt="remove_to_comparison"
                         class="w-8 h-8 hover:scale-110 transition-transform duration-200 cursor-pointer">
                    <span class="text-center text-xs mt-1 w-14">Rimuovi dal Confronto</span>
                </div>

                <h3 class="ml-9 mr-9 font-bold text-center underline italic">Asteroide Selezionato</h3>

                <!-- Icona Aggiungi al Confronto -->
                <div class="flex flex-col items-center">
                    <img src="{{ asset('media/icons/add.png') }}" alt="add_to_comparison"
                         class="w-8 h-8 hover:scale-110 transition-transform duration-200 cursor-pointer">
                    <span class="text-center text-xs mt-1 w-14">Aggiungi al Confronto</span>
                </div>
            @else
                <div class="flex ml-16 items-center justify-center">
                    <h3 class="font-bold underline italic text-center">Nessun Asteroide Selezionato</h3>
                </div>


            @endif
        </div>
    </div>



    @if(isset($asteroidData))
        <p class = "text-center -mt-16 mb-8 text-xl">{{ $asteroidData['name'] }}</p>
        <ul class="text-left text-[14px]">
            <li>
                <strong class="ml-1">Diametro calcolato:</strong>
                {{ !empty($asteroidData['diameter']) ? number_format($asteroidData['diameter'],2) . ' m' : 'Non disponibile' }}
                @if (!empty($asteroidData['diameter_uncertainty']))
                    ± {{ $asteroidData['diameter_uncertainty'] }} m
                @endif
            </li>

            @if(isset($sentrySummary))
                <li>
                    <strong title="Massa stimata considerando un corpo sferico uniforme con densità di 2.6 g/cm³. È solo una stima approssimativa."
                            class="cursor-help px-1 rounded">
                        Massa Stimata:
                    </strong>
                    {{ number_format($sentrySummary['mass'], 2) }} kg
                </li>
            @endif

            <li>
                <strong class="ml-1">Distanza di approccio:</strong>
                @if(!empty($asteroidData['miss_distance_km']))
                    <span class="text-yellow-500 font-bold underline cursor-pointer relative group"
                          onclick="showDistancePopup({{ $asteroidData['miss_distance_lunar'] ?? 'null' }})">
                                      {{ $asteroidData['miss_distance_km'] }} km
                                    <span class="absolute left-1/2 transform -translate-x-1/2 hidden group-hover:block bg-gray-800 text-white text-[14px] rounded-2xl px-2 py-1 w-auto shadow-white shadow-offset-y-[-5px] shadow-2xl z-10 mt-1">
                                        Clicca qui per vedere una rappresentazione realistica della distanza
                                    </span>
                                </span>
                @else
                    Non disponibile
                @endif
            </li>

            <li>
                <strong class="ml-1">Velocità attuale:</strong>
                {{ !empty($asteroidData['velocity_km_s']) ? $asteroidData['velocity_km_s'] . ' km/s' : 'Non disponibile' }}
            </li>

            <li>
                <strong class="ml-1">Potenzialmente Pericoloso:</strong>
                @if (isset($asteroidData['is_hazardous']))
                    <span class="font-extrabold {{ $asteroidData['is_hazardous'] ? 'text-red-500' : 'text-green-500' }}">
                                        {{ $asteroidData['is_hazardous'] ? 'Sì' : 'No' }}
                                </span>
                @else
                    Non disponibile
                @endif
            </li>

            <li>
                <strong class="ml-1">Possibile Impatto Futuro:</strong>
                @if (isset($asteroidData['is_sentry_object']))
                    <span class="font-extrabold {{ $asteroidData['is_sentry_object'] ? 'text-red-500' : 'text-green-500' }}">
                                        {{ $asteroidData['is_sentry_object'] ? 'Sì' : 'No' }}

                        @if ($asteroidData['is_sentry_object'])
                            >
                            <a href="{{ route('sentry.show', ['des' => $asteroidData['id']]) }}"
                               class="text-yellow-500 font-bold underline">
                                            Dettagli
                                        </a>
                        @endif

                                </span>
                @else
                    Non disponibile
                @endif
            </li>


            @if(isset($sentrySummary))
                <li>
                    <strong title="La probabilità cumulativa che l'impatto avvenga. Il calcolo è complesso e può variare anche di un ordine di grandezza."
                            class="cursor-help px-1 rounded">
                        Probabilità di Impatto Stimata:
                    </strong>
                    <span class="underline text-yellow-500 font-bold cursor-pointer"
                          title="{{ number_format($sentrySummary['ip'], 10) }} %">
                                      1 su {{ number_format(1 / $sentrySummary['ip'], 0, ',', '.') }}
                                </span>
                </li>

                <li>
                    <strong title="Energia cinetica al momento dell'impatto: 0.5 × massa × velocità², misurata in Megatoni di TNT."
                            class="cursor-help px-1 rounded">
                        Energia di Impatto Stimata:
                    </strong>
                    {{ $sentrySummary['energy'] * 1000 }} kT
                </li>
            @endif
        </ul>

    @elseif(isset($error))
        <div class="text-red-400">{{ $error }}</div>
    @endif

</div>

