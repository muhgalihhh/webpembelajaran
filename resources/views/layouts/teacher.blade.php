<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard Guru' }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-sans bg-gray-100">
    <div wire:loading class="fixed inset-0 z-50 flex items-center justify-center bg-gray-100 bg-opacity-75">
        <div class="w-16 h-16 border-4 border-blue-500 rounded-full border-t-transparent animate-spin"></div>
    </div>
    <div x-data="{ sidebarCollapsed: false, mobileSidebarOpen: false }" @keydown.escape.window="mobileSidebarOpen = false" class="flex flex-col h-screen">
        <x-ui.teacher.navbar />
        <div class="flex flex-1 overflow-hidden">

            <x-ui.teacher.sidebar-teacher />

            {{-- Konten Utama --}}
            <div class="flex flex-col flex-1">
                <main class="flex-1 overflow-y-auto">
                    @if (isset($pageHeader))
                        <div class="p-4 bg-white border-b border-gray-200 shadow-sm sm:p-6">
                            {{ $pageHeader }}
                        </div>
                    @endif
                    <div class="p-4 sm:p-6">
                        {{ $slot }}
                    </div>
                </main>
                <footer class="p-4 text-sm font-bold text-center text-white bg-[#4A90E2]">
                    © 2025 MEDPEM-DIGITAL™ BY RAHMAT ALFAJRI
                </footer>
            </div>
        </div>
    </div>
    <x-ui.alert-popup />
    <x-ui.flash-message />
    <x-ui.logout-confirmation />

    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>

    @livewireScripts
</body>

</html>
