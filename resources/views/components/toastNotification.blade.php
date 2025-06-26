@if(session('error') || session('success') || session('warning'))
    <div class="fixed bottom-5 right-5 z-50 space-y-3">
        @if(session('error'))
            <div id="toast-error"
                 class="bg-red-600 text-white px-6 py-4 rounded-lg shadow-lg animate-fade-in"
                 role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @if(session('success'))
            <div id="toast-success"
                 class="bg-green-600 text-white px-6 py-4 rounded-lg shadow-lg animate-fade-in"
                 role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('warning'))
            <div id="toast-warning"
                 class="bg-blue-600 text-white px-6 py-4 rounded-lg shadow-lg animate-fade-in"
                 role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M13 16h-1v-4h-1m1-4h.01M12 18h.01"/>
                    </svg>
                    <span>{{ session('warning') }}</span>
                </div>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            ['toast-error', 'toast-success', 'toast-warning'].forEach(function (id) {
                const toast = document.getElementById(id);
                if (toast) {
                    setTimeout(() => {
                        toast.classList.add('fade-out');
                        setTimeout(() => toast.remove(), 500);
                    }, 6000);
                }
            });
        });
    </script>

    <style>
        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(20px);}
            to {opacity: 1; transform: translateY(0);}
        }

        @keyframes fadeOut {
            from {opacity: 1; transform: translateY(0);}
            to {opacity: 0; transform: translateY(20px);}
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        .fade-out {
            animation: fadeOut 0.5s ease-in forwards;
        }
    </style>
@endif
