<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css'])
    @livewireStyles
    <style>
        /* Custom styles for consistent dashboard appearance */
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #EBF3FF;
            color: #333;
        }

        .h-screen {
            min-height: 100vh;
        }

        .bg-[#4A90E2] {
            background-color: #4A90E2;
        }

        .text-white {
            color: #fff;
        }

        .shadow-lg {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        /* Ensure sidebar is above background content but below modals/loaders */
        .z-20 {
            z-index: 20;
        }

        /* Header should be above sidebar */
        .z-30 {
            z-index: 30;
        }

        .border-b {
            border-bottom: 1px solid #E0E6ED;
        }

        .border-blue-700 {
            border-color: #3866A6;
        }

        .flex-1 {
            flex-grow: 1;
        }

        .overflow-hidden {
            overflow: hidden;
        }

        .bg-[#EBF3FF] {
            background-color: #EBF3FF;
        }

        .p-6 {
            padding: 1.5rem;
        }

        .relative {
            position: relative;
        }

        .absolute {
            position: absolute;
        }

        .inset-0 {
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }

        .z-0 {
            z-index: 0;
        }

        .flex {
            display: flex;
        }

        .items-center {
            align-items: center;
        }

        .justify-center {
            justify-content: center;
        }

        .opacity-10 {
            opacity: 0.1;
        }

        .object-contain {
            object-fit: contain;
        }

        .w-full {
            width: 100%;
        }

        .z-10 {
            z-index: 10;
        }

        .w-64 {
            width: 16rem;
        }

        .flex-col {
            flex-direction: column;
        }

        .h-16 {
            height: 4rem;
        }

        .px-6 {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }

        .py-3 {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }

        .text-xl {
            font-size: 1.25rem;
            line-height: 1.75rem;
        }

        .font-bold {
            font-weight: 700;
        }

        .shadow-md {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .text-gray-800 {
            color: #2D3748;
        }

        .text-xs {
            font-size: 0.75rem;
            line-height: 1rem;
        }

        .text-center {
            text-align: center;
        }

        .rounded-t-lg {
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }

        .shadow-inner {
            box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06);
        }

        /* Sidebar navigation styles */
        .sidebar-nav-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #E0E6ED;
            text-decoration: none;
            transition: background-color 0.15s ease-in-out, color 0.15s ease-in-out;
            margin-bottom: 0.25rem;
            border: 1px solid black;
            background-color: #9d9d9d;
            white-space: nowrap;
            /* Prevent text wrapping when shrunk */
        }

        .sidebar-nav-item:hover {
            background-color: #343434;
            color: #fff;
        }

        .sidebar-nav-item.active {
            background-color: #575757;
            color: #fff;
            box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06);
        }

        .sidebar-nav-icon {
            margin-right: 0.75rem;
            font-size: 1.125rem;
        }

        /* Wajib ada untuk x-cloak */
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="font-sans leading-normal tracking-normal bg-gray-100">

    {{-- Alert Popups (from x-ui.alert-popup component) --}}
    @if (session()->has('success'))
        <x-ui.alert-popup type="success" :message="session('success')" />
    @endif

    @if (session()->has('error'))
        <x-ui.alert-popup type="error" :message="session('error')" />
    @endif

    <div wire:loading.delay class="fixed inset-0 bg-black bg-opacity-50 z-[99999] flex items-center justify-center"
        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100">
        <div class="flex items-center p-6 space-x-3 bg-white rounded-lg shadow-xl"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100">
            <div class="w-8 h-8 border-4 border-blue-600 rounded-full animate-spin border-t-transparent"></div>
            <span class="text-lg font-semibold text-gray-700">Loading...</span>
        </div>
    </div>

    <x-ui.dashboard-nav />

    {{-- Main layout container with Alpine.js state for sidebar --}}
    <div class="flex h-screen bg-[#EBF3FF]" x-data="{ sidebarOpen: true }">

        {{-- Sidebar Component --}}
        <div class="relative z-20 flex flex-col h-screen text-white bg-gray-300 shadow-lg"
            :class="{ 'w-64': sidebarOpen, 'w-20': !sidebarOpen }" style="transition: width 0.3s ease-in-out;">

            {{-- Toggle Button for Sidebar --}}
            <button @click="sidebarOpen = !sidebarOpen"
                class="absolute z-30 p-2 text-white bg-blue-700 rounded-full shadow-lg top-4 focus:outline-none"
                :class="{ 'left-60': sidebarOpen, 'left-16': !sidebarOpen }" {{-- Dynamic left position --}}
                style="transition: left 0.3s ease-in-out; transform: translateX(-50%);">
                <i class="fas" :class="sidebarOpen ? 'fa-chevron-left' : 'fa-chevron-right'"></i>
            </button>

            {{-- Sidebar Content (originally from x-ui.sidebar-nav) --}}
            <nav class="flex-1 space-y-2 overflow-hidden" x-show="sidebarOpen"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                {{-- Name dan Greeting --}}
                <div class="p-2 mb-4 text-sm font-semibold text-left bg-blue-900 ">
                    @auth
                        <h3 class="text-lg font-semibold">
                            Welcome, <span x-show="sidebarOpen">{{ Auth::user()->name }}!</span>
                        </h3>
                        @if (Auth::user()->hasRole('admin'))
                            <div class="text-sm text-orange-300" x-show="sidebarOpen">
                                ({{ Auth::user()->getRoleNames()->first() }})
                            </div>
                            <div class="text-sm text-gray-300" x-show="sidebarOpen">({{ Auth::user()->email }})</div>
                        @elseif (Auth::user()->hasRole('guru'))
                            <div class="text-sm text-orange-300" x-show="sidebarOpen">
                                ({{ Auth::user()->getRoleNames()->first() }})</div>
                            <span class="text-sm text-gray-300" x-show="sidebarOpen">({{ Auth::user()->email }})</span>
                        @endif
                    @else
                        <span x-show="sidebarOpen">Welcome to the Dashboard!</span>
                    @endauth
                </div>
                {{-- Admin Navigation Links --}}
                @if (Auth::user()->hasRole('admin'))
                    <a href="{{ route('admin.dashboard') }}" wire:navigate
                        class="sidebar-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt sidebar-nav-icon"></i> <span
                            x-show="sidebarOpen">Dashboard</span>
                    </a>
                    <a href="{{ route('admin.manage-teachers') }}" wire:navigate
                        class="sidebar-nav-item {{ request()->routeIs('admin.manage-teachers') ? 'active' : '' }}">
                        <i class="fas fa-chalkboard-teacher sidebar-nav-icon"></i> <span x-show="sidebarOpen">Manage
                            Teachers</span>
                    </a>
                    <a href="{{ route('admin.manage-students') }}" wire:navigate
                        class="sidebar-nav-item {{ request()->routeIs('admin.manage-students') ? 'active' : '' }}">
                        <i class="fas fa-user-graduate sidebar-nav-icon"></i> <span x-show="sidebarOpen">Manage
                            Students</span>
                    </a>
                    <a href="{{ route('admin.manage-classes') }}" wire:navigate
                        class="sidebar-nav-item {{ request()->routeIs('admin.manage-classes') ? 'active' : '' }}">
                        <i class="fas fa-school sidebar-nav-icon"></i> <span x-show="sidebarOpen">Manage Classes</span>
                    </a>
                    <a href="{{ route('admin.manage-subjects') }}" wire:navigate
                        class="sidebar-nav-item {{ request()->routeIs('admin.manage-subjects') ? 'active' : '' }}">
                        <i class="fas fa-book sidebar-nav-icon"></i> <span x-show="sidebarOpen">Manage Subjects</span>
                    </a>
                @endif

                {{-- Teacher Navigation Links --}}
                @if (Auth::user()->hasRole('guru'))
                    <a href="{{ route('teacher.dashboard') }}" wire:navigate
                        class="sidebar-nav-item {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt sidebar-nav-icon"></i> <span
                            x-show="sidebarOpen">Dashboard</span>
                    </a>
                    <a href="{{ route('teacher.create-quiz') }}" wire:navigate
                        class="sidebar-nav-item {{ request()->routeIs('teacher.create-quiz') ? 'active' : '' }}">
                        <i class="fas fa-question-circle sidebar-nav-icon"></i> <span x-show="sidebarOpen">Buat
                            Soal/Kuis</span>
                    </a>
                    <a href="{{ route('teacher.upload-task') }}" wire:navigate
                        class="sidebar-nav-item {{ request()->routeIs('teacher.upload-task') ? 'active' : '' }}">
                        <i class="fas fa-upload sidebar-nav-icon"></i> <span x-show="sidebarOpen">Upload Tugas
                            Siswa</span>
                    </a>
                    <a href="{{ route('teacher.upload-material') }}" wire:navigate
                        class="sidebar-nav-item {{ request()->routeIs('teacher.upload-material') ? 'active' : '' }}">
                        <i class="fas fa-file-upload sidebar-nav-icon"></i> <span x-show="sidebarOpen">Upload Materi
                            Mapel</span>
                    </a>
                    <a href="{{ route('teacher.check-rank') }}" wire:navigate
                        class="sidebar-nav-item {{ request()->routeIs('teacher.check-rank') ? 'active' : '' }}">
                        <i class="fas fa-medal sidebar-nav-icon"></i> <span x-show="sidebarOpen">Cek Peringkat
                            Siswa</span>
                    </a>
                @endif
            </nav>
        </div>

        {{-- Main Content Area --}}
        <div class="flex flex-col flex-1 overflow-hidden" :class="{ 'ml-64': sidebarOpen, 'ml-20': !sidebarOpen }"
            {{-- Dynamic margin-left --}} style="transition: margin-left 0.3s ease-in-out;">
            <main class="flex-1 overflow-y-auto bg-[#EBF3FF] p-6 relative">
                {{-- Background logo for content area --}}
                <div class="absolute inset-0 z-0 flex items-center justify-center opacity-10">
                    <img src="{{ asset('images/logo sd.png') }}" alt="SD Logo Background"
                        class="object-contain w-full h-full">
                </div>
                {{-- Content slot (where Livewire components will be rendered) --}}
                <div class="relative" x-data="{}" x-cloak x-transition:enter="ease-out duration-500"
                    x-transition:enter-start="opacity-0 transform translate-y-4"
                    x-transition:enter-end="opacity-100 transform translate-y-0">
                    {{ $slot }}
                </div>
            </main>

            {{-- Footer Section --}}
            <footer
                class="w-full py-3 text-xs font-bold text-center text-white bg-[#4A90E2] rounded-t-lg shadow-inner z-20">
                &copy; {{ date('Y') }} {{ config('app.name') }}
            </footer>
        </div>
    </div>

    @livewireScripts
    <x-form.password-toggle />
    <x-ui.confirm-dialog />
    <x-ui.logout-confirmation />
    @vite(['resources/js/app.js'])
</body>

</html>
