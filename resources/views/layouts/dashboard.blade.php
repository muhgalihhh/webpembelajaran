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

        /* Sidebar styles */
        .sidebar {
            position: absolute;
            height: 100vh;
            transition: width 0.3s ease-in-out;
            overflow: hidden;
        }

        .sidebar.expanded {
            width: 16rem;
            /* w-64 = 256px = 16rem */
        }

        .sidebar.collapsed {
            width: 5rem;
            /* w-20 = 80px = 5rem */
        }

        /* Main content container */
        .main-content {
            transition: margin-left 0.3s ease-in-out;
        }

        .main-content.expanded {
            margin-left: 16rem;
        }

        .main-content.collapsed {
            margin-left: 5rem;
        }

        /* Toggle button positioning */
        .toggle-btn {
            position: fixed;
            top: 1rem;
            z-index: 30;
            transition: left 0.3s ease-in-out;
            transform: translateX(50%);
        }

        .toggle-btn.expanded {
            left: 16rem;
        }

        .toggle-btn.collapsed {
            left: 5rem;
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
            transition: all 0.15s ease-in-out;
            margin-bottom: 0.25rem;
            border: 1px solid #374151;
            background-color: #6B7280;
            white-space: nowrap;
            position: relative;
            overflow: hidden;
        }

        .sidebar-nav-item:hover {
            background-color: #374151;
            color: #fff;
        }

        .sidebar-nav-item.active {
            background-color: #1F2937;
            color: #fff;
            box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06);
        }

        .sidebar-nav-icon {
            margin-right: 0.75rem;
            font-size: 1.125rem;
            min-width: 1.125rem;
            text-align: center;
        }

        /* Collapsed sidebar styles */
        .sidebar.collapsed .sidebar-nav-item {
            justify-content: center;
            padding: 0.75rem 0.5rem;
        }

        .sidebar.collapsed .sidebar-nav-icon {
            margin-right: 0;
        }

        .sidebar.collapsed .nav-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
            transition: opacity 0.2s ease-in-out;
        }

        .sidebar.expanded .nav-text {
            opacity: 1;
            width: auto;
            transition: opacity 0.2s ease-in-out 0.1s;
        }

        /* User info section */
        .user-info {
            padding: 1rem;
            margin-bottom: 1rem;
            background-color: #1E40AF;
            color: white;
        }

        .sidebar.collapsed .user-info {
            text-align: center;
            padding: 0.5rem;
        }

        .sidebar.collapsed .user-details {
            opacity: 0;
            height: 0;
            overflow: hidden;
            transition: opacity 0.2s ease-in-out;
        }

        .sidebar.expanded .user-details {
            opacity: 1;
            height: auto;
            transition: opacity 0.2s ease-in-out 0.1s;
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
    <div class="h-screen bg-[#EBF3FF]" x-data="{ sidebarOpen: true }">

        {{-- Sidebar Component --}}
        <div class="flex flex-col text-white bg-gray-400 shadow-lg sidebar"
            :class="{ 'expanded': sidebarOpen, 'collapsed': !sidebarOpen }">

            {{-- Toggle Button for Sidebar --}}
            <button @click="sidebarOpen = !sidebarOpen"
                class="p-2 text-white bg-blue-700 rounded-full shadow-lg toggle-btn focus:outline-none"
                :class="{ 'expanded': sidebarOpen, 'collapsed': !sidebarOpen }">
                <i class="fas" :class="sidebarOpen ? 'fa-chevron-left' : 'fa-chevron-right'"></i>
            </button>

            {{-- User Info Section --}}
            <div class="user-info">
                @auth
                    <div class="flex items-center justify-center" :class="{ 'mb-2': sidebarOpen }">
                        <i class="text-2xl fas fa-user-circle" :class="{ 'mr-3': sidebarOpen }"></i>
                        <div class="user-details">
                            <h3 class="text-lg font-semibold">
                                Welcome, {{ Auth::user()->name }}!
                            </h3>
                            @if (Auth::user()->hasRole('admin'))
                                <div class="text-sm text-orange-300">({{ Auth::user()->getRoleNames()->first() }})</div>
                                <div class="text-sm text-gray-300">({{ Auth::user()->email }})</div>
                            @elseif (Auth::user()->hasRole('guru'))
                                <div class="text-sm text-orange-300">({{ Auth::user()->getRoleNames()->first() }})</div>
                                <span class="text-sm text-gray-300">({{ Auth::user()->email }})</span>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="user-details">
                        <span>Welcome to the Dashboard!</span>
                    </div>
                @endauth
            </div>

            {{-- Sidebar Content --}}
            <nav class="flex-1 px-2 space-y-2 overflow-hidden">
                {{-- Admin Navigation Links --}}
                @if (Auth::user()->hasRole('admin'))
                    <a href="{{ route('admin.dashboard') }}" wire:navigate
                        class="sidebar-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt sidebar-nav-icon"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                    <a href="{{ route('admin.manage-teachers') }}" wire:navigate
                        class="sidebar-nav-item {{ request()->routeIs('admin.manage-teachers') ? 'active' : '' }}">
                        <i class="fas fa-chalkboard-teacher sidebar-nav-icon"></i>
                        <span class="nav-text">Manage Teachers</span>
                    </a>
                    <a href="{{ route('admin.manage-students') }}" wire:navigate
                        class="sidebar-nav-item {{ request()->routeIs('admin.manage-students') ? 'active' : '' }}">
                        <i class="fas fa-user-graduate sidebar-nav-icon"></i>
                        <span class="nav-text">Manage Students</span>
                    </a>
                    <a href="{{ route('admin.manage-classes') }}" wire:navigate
                        class="sidebar-nav-item {{ request()->routeIs('admin.manage-classes') ? 'active' : '' }}">
                        <i class="fas fa-school sidebar-nav-icon"></i>
                        <span class="nav-text">Manage Classes</span>
                    </a>
                    <a href="{{ route('admin.manage-subjects') }}" wire:navigate
                        class="sidebar-nav-item {{ request()->routeIs('admin.manage-subjects') ? 'active' : '' }}">
                        <i class="fas fa-book sidebar-nav-icon"></i>
                        <span class="nav-text">Manage Subjects</span>
                    </a>
                @endif

                {{-- Teacher Navigation Links --}}
                @if (Auth::user()->hasRole('guru'))
                    <a href="{{ route('teacher.dashboard') }}" wire:navigate
                        class="sidebar-nav-item {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt sidebar-nav-icon"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                    <a href="{{ route('teacher.create-quiz') }}" wire:navigate
                        class="sidebar-nav-item {{ request()->routeIs('teacher.create-quiz') ? 'active' : '' }}">
                        <i class="fas fa-question-circle sidebar-nav-icon"></i>
                        <span class="nav-text">Buat Soal/Kuis</span>
                    </a>
                    <a href="{{ route('teacher.upload-task') }}" wire:navigate
                        class="sidebar-nav-item {{ request()->routeIs('teacher.upload-task') ? 'active' : '' }}">
                        <i class="fas fa-upload sidebar-nav-icon"></i>
                        <span class="nav-text">Upload Tugas Siswa</span>
                    </a>
                    <a href="{{ route('teacher.upload-material') }}" wire:navigate
                        class="sidebar-nav-item {{ request()->routeIs('teacher.upload-material') ? 'active' : '' }}">
                        <i class="fas fa-file-upload sidebar-nav-icon"></i>
                        <span class="nav-text">Upload Materi Mapel</span>
                    </a>
                    <a href="{{ route('teacher.check-rank') }}" wire:navigate
                        class="sidebar-nav-item {{ request()->routeIs('teacher.check-rank') ? 'active' : '' }}">
                        <i class="fas fa-medal sidebar-nav-icon"></i>
                        <span class="nav-text">Cek Peringkat Siswa</span>
                    </a>
                @endif
            </nav>
        </div>

        {{-- Main Content Area --}}
        <div class="flex flex-col h-screen main-content"
            :class="{ 'expanded': sidebarOpen, 'collapsed': !sidebarOpen }">
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
