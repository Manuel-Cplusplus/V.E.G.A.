{{--
    V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
    Copyright (c) 2025 Manuel Carlucci

    Licensed under CC BY-NC-SA 4.0: http://creativecommons.org/licenses/by-nc-sa/4.0/
--}}

<ul class="mt-1 list-disc pl-5 text-gray-300 space-y-1">
    @foreach ($versions as $version)
        <li>
            <details class="ml-2">
                <summary class="cursor-pointer">
                    <a href="{{ route('learn.show', ['user' => $structure->user_id, 'structure' => $structure->id, 'version' => $version->id]) }}"
                       class="text-green-400 underline hover:text-green-300">
                        Versione {{ $version->version }}
                    </a>
                    - Feedback: <em>"{{ optional($version->feedback)->feedback ?? 'Nessun feedback' }}"</em>

                    <form id="delete-form-{{ $version->id }}"
                          action="{{ route('learn.destroy', ['structure' => $structure->id, 'version' => $version->id]) }}"
                          method="POST" class="inline-flex items-center ml-2 align-middle">
                        @csrf
                        @method('DELETE')
                        <button type="button"
                                onclick="confirmDelete({{ $version->id }})"
                                class="text-red-500 hover:text-red-700 text-sm flex items-center"
                                title="Elimina">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </form>
                </summary>

                @if (isset($version->children) && count($version->children) > 0)
                    @include('tools.learn.partials.child-version', ['versions' => $version->children, 'structure' => $structure])
                @endif
            </details>
        </li>
    @endforeach
</ul>
