<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Dashboard' }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-sans bg-white">
    <div x-data="{ sidebarCollapsed: false, mobileSidebarOpen: false }" @keydown.escape.window="mobileSidebarOpen = false" class="flex h-screen bg-gray-100">

        <x-ui.admin.sidebar-admin />

        {{-- PASTIKAN BARIS DI BAWAH INI SAMA PERSIS --}}
        <div class="flex flex-col flex-1" :class="{ 'lg:ml-64': !sidebarCollapsed, 'lg:ml-20': sidebarCollapsed }">

            <x-ui.admin.navbar />

            <main class="flex-1 p-6 overflow-y-auto bg-gray-50">
                {{ $slot }}
            </main>

            <footer class="p-4 text-sm font-bold text-center text-white bg-[#4A90E2]">
                © 2025 MEDPEM-DIGITAL™ BY RAHMAT ALFAJRI
            </footer>
        </div>
    </div>

    @livewireScripts
</body>

</html>
