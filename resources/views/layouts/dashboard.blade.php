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

<body class="font-sans bg-gray-100">
    <div x-data="{ sidebarCollapsed: false, mobileSidebarOpen: false }" @keydown.escape.window="mobileSidebarOpen = false" class="flex flex-col h-screen">

        {{-- 1. Navbar di bagian paling atas --}}
        <x-ui.admin.navbar />

        {{-- 2. Area di bawah Navbar --}}
        <div class="flex flex-1 overflow-hidden">
            {{-- Komponen Sidebar --}}
            <x-ui.admin.sidebar-admin />

            {{-- 3. Konten utama di sebelah Sidebar --}}
            <div class="flex flex-col flex-1">
                <main class="flex-1 overflow-y-auto">
                    {{-- Header Halaman (diisi dari setiap view) --}}
                    @if (isset($pageHeader))
                        <div class="p-4 bg-white border-b border-gray-200 shadow-sm sm:p-6">
                            {{ $pageHeader }}
                        </div>
                    @endif

                    {{-- Konten Utama Halaman --}}
                    <div class="p-4 sm:p-6">
                        {{ $slot }}
                    </div>
                </main>

                {{-- Footer --}}
                <footer class="p-4 text-sm font-bold text-center text-white bg-[#4A90E2]">
                    © 2025 MEDPEM-DIGITAL™ BY RAHMAT ALFAJRI
                </footer>
            </div>
        </div>
    </div>

    @livewireScripts
</body>

</html>
