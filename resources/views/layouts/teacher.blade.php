<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full overflow-hidden">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard Guru' }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        /* Reset dan base styling */
        * {
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            overflow: hidden;
            position: fixed;
            width: 100%;
        }

        /* Container utama dengan tinggi yang fix */
        .app-container {
            height: 100vh;
            height: 100dvh;
            /* Dynamic viewport height */
            position: relative;
            overflow: hidden;
        }

        /* iOS Safari specific fix */
        @supports (-webkit-touch-callout: none) {
            .app-container {
                height: -webkit-fill-available;
            }
        }

        /* Prevent zoom pada input */
        input,
        textarea,
        select,
        trix-editor {
            font-size: 16px !important;
            transform-origin: left top;
        }

        /* Smooth scrolling untuk content area */
        .content-scroll {
            -webkit-overflow-scrolling: touch;
            overscroll-behavior: none;
            scroll-behavior: smooth;
        }

        /* Footer tetap di posisi absolute bottom */
        .fixed-footer {
            position: relative;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 10;
        }

        /* Main content dengan padding bottom untuk footer */
        .main-with-footer {
            padding-bottom: 60px;
            /* Sesuaikan dengan tinggi footer */
        }

        /* Keyboard detection */
        @media screen and (max-height: 500px) {
            .keyboard-open .fixed-footer {
                display: none;
            }
        }
    </style>
</head>

<body class="overflow-hidden font-sans bg-gray-100">
    <div wire:loading class="fixed inset-0 z-50 flex items-center justify-center bg-gray-100 bg-opacity-75">
        <div class="w-16 h-16 border-4 border-blue-500 rounded-full border-t-transparent animate-spin"></div>
    </div>

    <div x-data="{
        sidebarCollapsed: false,
        mobileSidebarOpen: false,
        keyboardOpen: false,
        init() {
            // Deteksi keyboard
            const initialHeight = window.innerHeight;
    
            const detectKeyboard = () => {
                const currentHeight = window.innerHeight;
                const heightDifference = initialHeight - currentHeight;
    
                // Jika tinggi berkurang lebih dari 150px, keyboard terbuka
                this.keyboardOpen = heightDifference > 150;
    
                if (this.keyboardOpen) {
                    document.body.classList.add('keyboard-open');
                } else {
                    document.body.classList.remove('keyboard-open');
                }
            };
    
            window.addEventListener('resize', detectKeyboard);
    
            // Visual viewport API jika tersedia
            if (window.visualViewport) {
                window.visualViewport.addEventListener('resize', detectKeyboard);
            }
        }
    }" @keydown.escape.window="mobileSidebarOpen = false" class="flex flex-col app-container">

        <!-- Navbar -->
        <div class="flex-shrink-0">
            <x-ui.teacher.navbar />
        </div>

        <!-- Main content wrapper dengan posisi relative -->
        <div class="relative flex flex-1 overflow-hidden">
            <x-ui.teacher.sidebar-teacher />

            <!-- Content area -->
            <div class="relative flex-1 overflow-hidden">
                <!-- Scrollable content -->
                <main class="h-full overflow-y-auto content-scroll main-with-footer">
                    @if (isset($pageHeader))
                        <div class="flex items-center p-4 bg-white border-b border-gray-200 shadow-sm sm:p-6">
                            {{ $pageHeader }}
                        </div>
                    @endif

                    <div class="p-4 sm:p-6">
                        {{ $slot }}
                    </div>
                </main>

                <!-- Fixed Footer -->
                <footer class="fixed-footer p-4 text-sm font-bold text-center text-white bg-[#4A90E2]"
                    x-show="!keyboardOpen || window.innerWidth >= 768"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform translate-y-full"
                    x-transition:enter-end="opacity-100 transform translate-y-0">
                    © 2025 MEDPEM-DIGITAL™ BY RAHMAT ALFAJRI
                </footer>
            </div>
        </div>
    </div>

    <x-ui.flash-message />
    <x-ui.logout-confirmation />
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    @livewireScripts
</body>

</html>
