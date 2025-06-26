{{--
    V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
    Copyright (c) 2025 Manuel Carlucci

    Licensed under CC BY-NC-SA 4.0: http://creativecommons.org/licenses/by-nc-sa/4.0/
--}}

@php
    use Illuminate\Support\Facades\Auth;
@endphp

@auth
    @php
        $allNotifications = Auth::user()->notifications()->get()->sortByDesc('created_at');
        $unreadNotifications = $allNotifications->whereNull('read_at');
        $notificationCount = $unreadNotifications->count();
    @endphp
@endauth

@guest
    @php
        $notificationCount = 0;
    @endphp
@endguest


<a href="#" id="notificationBtn"
   class="hover:bg-[#bef6] hover:rounded-md p-1 mr-5 transition-all duration-200 relative">
    @if($notificationCount > 0)
        <span class="material-symbols-outlined text-white font-light hover:text-sky-600">notifications_unread</span>
        <span class="absolute top-8 right-0 bg-blue-800 text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center transform translate-x-1/2 -translate-y-1/2">
            {{ $notificationCount }}
        </span>
    @else
        <span class=" mt-1 material-symbols-outlined text-sky-800 hover:text-sky-600">notifications</span>
    @endif
</a>

<!-- Dropdown Notifiche -->
<div id="notificationDropdownMenu"
     class="z-50 absolute top-full right-72 -translate-x-4 w-80 bg-[#0D101F] shadow-lg rounded-md opacity-0 invisible transition-opacity duration-300 max-h-60 overflow-y-auto scrollbar-thin scrollbar-thumb-white scrollbar-thumb-rounded scrollbar-track-[#0D101F]">

    @auth
        @if($allNotifications->count() > 0)
            @foreach($allNotifications as $notification)
                <a href="{{ route('notification.read', ['id' => $notification->id, 'asteroid_id' => $notification->data['asteroid_id'] ?? null]) }}"
                   class="block px-4 py-2 border-b border-white last:border-0 transition-all duration-200 hover:bg-gray-700 text-white font-semibold {{ $notification->read_at ? '' : 'bg-black' }}">
                    <p class="text-sm font-bold">ID: {{ $notification->data['asteroid_id'] ?? 'N/A' }}</p>
                    <p class="text-sm text-sky-400 font-semibold">Designation: {{ $notification->data['designation'] ?? 'N/A' }}</p>
                    <p class="text-xs text-gray-300 mt-1">{{ $notification->data['message'] ?? 'Nessun messaggio disponibile.' }}</p>
                </a>
            @endforeach
        @else
            <p class="block px-4 py-2 text-gray-500">Nessuna notifica riguardante gli asteroidi preferiti disponibile.</p>
        @endif
    @endauth

    @guest
        <p class="block px-4 py-2 text-gray-500">Accedi per visualizzare le notifiche.</p>
    @endguest
</div>



<style>
    /* Personalizza la larghezza della scrollbar */
    #notificationDropdownMenu::-webkit-scrollbar {
        width: 4px; /* Imposta la larghezza della scrollbar */
    }

    /* Colore e aspetto della parte della scrollbar che si muove */
    #notificationDropdownMenu::-webkit-scrollbar-thumb {
        background-color: white;
        border-radius: 10px;
    }

    /* Colore della traccia della scrollbar */
    #notificationDropdownMenu::-webkit-scrollbar-track {
        background-color: #0D101F;
    }
</style>

<script>
    /** Gestione Dropdown Notifiche **/
    const notificationBtn = document.getElementById("notificationBtn");
    const notificationDropdownMenu = document.getElementById("notificationDropdownMenu");
    let notificationHideTimeout;

    notificationBtn.addEventListener("mouseenter", () => {
        clearTimeout(notificationHideTimeout);
        notificationDropdownMenu.classList.remove("opacity-0", "invisible");
        notificationDropdownMenu.classList.add("opacity-100", "visible");
    });

    notificationDropdownMenu.addEventListener("mouseenter", () => {
        clearTimeout(notificationHideTimeout);
    });

    notificationBtn.addEventListener("mouseleave", () => {
        notificationHideTimeout = setTimeout(() => {
            notificationDropdownMenu.classList.add("opacity-0", "invisible");
            notificationDropdownMenu.classList.remove("opacity-100", "visible");
        }, 300);
    });

    notificationDropdownMenu.addEventListener("mouseleave", () => {
        notificationHideTimeout = setTimeout(() => {
            notificationDropdownMenu.classList.add("opacity-0", "invisible");
            notificationDropdownMenu.classList.remove("opacity-100", "visible");
        }, 300);
    });
</script>
