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
</head>

<body class="font-sans leading-normal tracking-normal bg-gray-100">

    {{-- Alert Popups --}}
    @if (session()->has('success'))
        <x-ui.alert-popup type="success" :message="session('success')" />
    @endif

    @if (session()->has('error'))
        <x-ui.alert-popup type="error" :message="session('error')" />
    @endif

    {{-- Loading Indicator --}}
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

    {{-- Dashboard Navigation (Top Bar) --}}
    <x-ui.dashboard-nav />

    {{-- Main layout container with Alpine.js state for sidebar --}}
    <div class="flex h-screen bg-[#EBF3FF] pt-16" x-data="{
        sidebarOpen: false,
        isMobile: true
    }" x-init="// Initialize state based on window size
    const updateLayout = () => {
        isMobile = window.innerWidth < 768;
        if (isMobile) {
            sidebarOpen = false; // Always closed on mobile initially
        } else {
            sidebarOpen = true; // Always open on desktop
        }
    };
    
    // Set initial state
    updateLayout();
    
    // Update on window resize
    window.addEventListener('resize', updateLayout);"
        @toggle-sidebar.window="sidebarOpen = !sidebarOpen">

        {{-- Mobile Overlay for Sidebar --}}
        <div x-show="sidebarOpen && isMobile" x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="sidebarOpen = false"
            class="fixed inset-0 z-30 bg-black bg-opacity-50 md:hidden"></div>

        {{-- Sidebar --}}
        <div class="fixed top-0 left-0 z-40 flex flex-col h-full text-white transition-all duration-300 ease-in-out -translate-x-full bg-gray-800 shadow-lg md:translate-x-0"
            x-cloak
            :class="{
                // Desktop styles
                'w-64': sidebarOpen && !isMobile,
                'w-20': !sidebarOpen && !isMobile,
                'translate-x-0': !isMobile,
                // Mobile styles
                'w-64': isMobile,
                'translate-x-0': sidebarOpen && isMobile,
                '-translate-x-full': !sidebarOpen && isMobile
            }"
            style="top: 64px;">

            {{-- Toggle Button for Sidebar (Desktop Only) --}}
            <button @click="sidebarOpen = !sidebarOpen" x-show="!isMobile"
                class="absolute p-2 text-white bg-blue-700 rounded-full shadow-lg -right-4 top-4 focus:outline-none hover:bg-blue-800">
                <i class="transition-transform duration-200 fas"
                    :class="sidebarOpen ? 'fa-chevron-left' : 'fa-chevron-right'"></i>
            </button>

            {{-- Close Button for Mobile --}}
            <button @click="sidebarOpen = false" x-show="isMobile"
                class="absolute p-2 text-white bg-red-600 rounded-full shadow-lg -right-4 top-4 focus:outline-none hover:bg-red-700">
                <i class="fas fa-times"></i>
            </button>

            {{-- User Info Section --}}
            <div class="flex items-center p-4 bg-gray-900 border-b border-gray-700 user-info"
                :class="{
                    'justify-start': sidebarOpen || isMobile,
                    'justify-center': !sidebarOpen && !isMobile
                }">
                <i class="text-2xl fas fa-user-circle"
                    :class="{
                        'mr-3': sidebarOpen || isMobile,
                        'mb-0': sidebarOpen || isMobile,
                        'mb-2': !sidebarOpen && !isMobile
                    }"></i>
                <div x-show="sidebarOpen || isMobile" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0">
                    <h3 class="text-lg font-semibold">Welcome, {{ Auth::user()->name }}!</h3>
                    @if (Auth::user()->hasRole('admin'))
                        <div class="text-sm text-orange-300">({{ Auth::user()->getRoleNames()->first() }})</div>
                        <div class="text-sm text-gray-300">({{ Auth::user()->email }})</div>
                    @elseif (Auth::user()->hasRole('guru'))
                        <div class="text-sm text-orange-300">({{ Auth::user()->getRoleNames()->first() }})</div>
                        <span class="text-sm text-gray-300">({{ Auth::user()->email }})</span>
                    @endif
                </div>
            </div>

            {{-- Sidebar Content (Navigation Links) --}}
            <nav class="flex-1 px-2 py-4 space-y-2 overflow-y-auto">
                {{-- Admin Navigation Links --}}
                @if (Auth::user()->hasRole('admin'))
                    <a href="{{ route('admin.dashboard') }}" wire:navigate
                        class="flex items-center px-4 py-2 text-gray-300 transition duration-150 ease-in-out rounded-md hover:bg-gray-700"
                        :class="{ 'bg-gray-700 text-white': '{{ request()->routeIs('admin.dashboard') }}' }">
                        <i class="fas fa-tachometer-alt"
                            :class="{ 'mr-3': sidebarOpen || isMobile, 'mr-0': !sidebarOpen && !isMobile }"></i>
                        <span x-show="sidebarOpen || isMobile">Dashboard</span>
                    </a>
                    <a href="{{ route('admin.manage-teachers') }}" wire:navigate
                        class="flex items-center px-4 py-2 text-gray-300 transition duration-150 ease-in-out rounded-md hover:bg-gray-700"
                        :class="{ 'bg-gray-700 text-white': '{{ request()->routeIs('admin.manage-teachers') }}' }">
                        <i class="fas fa-chalkboard-teacher"
                            :class="{ 'mr-3': sidebarOpen || isMobile, 'mr-0': !sidebarOpen && !isMobile }"></i>
                        <span x-show="sidebarOpen || isMobile">Manage Teachers</span>
                    </a>
                    <a href="{{ route('admin.manage-students') }}" wire:navigate
                        class="flex items-center px-4 py-2 text-gray-300 transition duration-150 ease-in-out rounded-md hover:bg-gray-700"
                        :class="{ 'bg-gray-700 text-white': '{{ request()->routeIs('admin.manage-students') }}' }">
                        <i class="fas fa-user-graduate"
                            :class="{ 'mr-3': sidebarOpen || isMobile, 'mr-0': !sidebarOpen && !isMobile }"></i>
                        <span x-show="sidebarOpen || isMobile">Manage Students</span>
                    </a>
                    <a href="{{ route('admin.manage-classes') }}" wire:navigate
                        class="flex items-center px-4 py-2 text-gray-300 transition duration-150 ease-in-out rounded-md hover:bg-gray-700"
                        :class="{ 'bg-gray-700 text-white': '{{ request()->routeIs('admin.manage-classes') }}' }">
                        <i class="fas fa-school"
                            :class="{ 'mr-3': sidebarOpen || isMobile, 'mr-0': !sidebarOpen && !isMobile }"></i>
                        <span x-show="sidebarOpen || isMobile">Manage Classes</span>
                    </a>
                    <a href="{{ route('admin.manage-subjects') }}" wire:navigate
                        class="flex items-center px-4 py-2 text-gray-300 transition duration-150 ease-in-out rounded-md hover:bg-gray-700"
                        :class="{ 'bg-gray-700 text-white': '{{ request()->routeIs('admin.manage-subjects') }}' }">
                        <i class="fas fa-book"
                            :class="{ 'mr-3': sidebarOpen || isMobile, 'mr-0': !sidebarOpen && !isMobile }"></i>
                        <span x-show="sidebarOpen || isMobile">Manage Subjects</span>
                    </a>
                @endif

                {{-- Teacher Navigation Links --}}
                @if (Auth::user()->hasRole('guru'))
                    <a href="{{ route('teacher.dashboard') }}" wire:navigate
                        class="flex items-center px-4 py-2 text-gray-300 transition duration-150 ease-in-out rounded-md hover:bg-gray-700"
                        :class="{ 'bg-gray-700 text-white': '{{ request()->routeIs('teacher.dashboard') }}' }">
                        <i class="fas fa-tachometer-alt"
                            :class="{ 'mr-3': sidebarOpen || isMobile, 'mr-0': !sidebarOpen && !isMobile }"></i>
                        <span x-show="sidebarOpen || isMobile">Dashboard</span>
                    </a>
                    <a href="{{ route('teacher.create-quiz') }}" wire:navigate
                        class="flex items-center px-4 py-2 text-gray-300 transition duration-150 ease-in-out rounded-md hover:bg-gray-700"
                        :class="{ 'bg-gray-700 text-white': '{{ request()->routeIs('teacher.create-quiz') }}' }">
                        <i class="fas fa-question-circle"
                            :class="{ 'mr-3': sidebarOpen || isMobile, 'mr-0': !sidebarOpen && !isMobile }"></i>
                        <span x-show="sidebarOpen || isMobile">Buat Soal/Kuis</span>
                    </a>
                    <a href="{{ route('teacher.upload-task') }}" wire:navigate
                        class="flex items-center px-4 py-2 text-gray-300 transition duration-150 ease-in-out rounded-md hover:bg-gray-700"
                        :class="{ 'bg-gray-700 text-white': '{{ request()->routeIs('teacher.upload-task') }}' }">
                        <i class="fas fa-upload"
                            :class="{ 'mr-3': sidebarOpen || isMobile, 'mr-0': !sidebarOpen && !isMobile }"></i>
                        <span x-show="sidebarOpen || isMobile">Upload Tugas Siswa</span>
                    </a>
                    <a href="{{ route('teacher.upload-material') }}" wire:navigate
                        class="flex items-center px-4 py-2 text-gray-300 transition duration-150 ease-in-out rounded-md hover:bg-gray-700"
                        :class="{ 'bg-gray-700 text-white': '{{ request()->routeIs('teacher.upload-material') }}' }">
                        <i class="fas fa-file-upload"
                            :class="{ 'mr-3': sidebarOpen || isMobile, 'mr-0': !sidebarOpen && !isMobile }"></i>
                        <span x-show="sidebarOpen || isMobile">Upload Materi Mapel</span>
                    </a>
                    <a href="{{ route('teacher.check-rank') }}" wire:navigate
                        class="flex items-center px-4 py-2 text-gray-300 transition duration-150 ease-in-out rounded-md hover:bg-gray-700"
                        :class="{ 'bg-gray-700 text-white': '{{ request()->routeIs('teacher.check-rank') }}' }">
                        <i class="fas fa-medal"
                            :class="{ 'mr-3': sidebarOpen || isMobile, 'mr-0': !sidebarOpen && !isMobile }"></i>
                        <span x-show="sidebarOpen || isMobile">Cek Peringkat Siswa</span>
                    </a>
                @endif
            </nav>
        </div>

        {{-- Main Content Area --}}
        <div class="flex flex-col flex-1 overflow-x-hidden overflow-y-auto bg-[#EBF3FF] transition-all duration-300 ease-in-out ml-0 md:ml-20"
            x-cloak
            :class="{
                'md:ml-64': sidebarOpen && !isMobile,
                'md:ml-20': !sidebarOpen && !isMobile
            }">
            <main class="relative flex-1 p-6">
                {{-- Background logo for content area --}}
                <div class="absolute inset-0 z-0 flex items-center justify-center opacity-10">
                    <img src="{{ asset('images/logo sd.png') }}" alt="SD Logo Background"
                        class="object-contain w-full h-full">
                </div>
                {{-- Content slot --}}
                <div class="relative z-10" x-data="{}" x-cloak x-transition:enter="ease-out duration-500"
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
