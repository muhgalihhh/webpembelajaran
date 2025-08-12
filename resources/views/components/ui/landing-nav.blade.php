<header x-data="{ mobileMenuOpen: false, profileDropdownOpen: false }" class="bg-[#4A90E2] text-white shadow-md w-full sticky top-0 z-50">

    <!-- Main Header -->
    <div class="px-4 py-3 sm:px-6">
        <div class="flex items-center justify-between">
            <!-- Logo Section (Left) -->
            <div class="flex items-center flex-shrink-0 space-x-3">
                <x-ui.logo-nav />
            </div>

            <!-- Center Navigation (Desktop Only) -->
            @auth
                <div class="justify-center flex-1 hidden lg:flex">
                    <nav class="flex items-center space-x-1">
                        @role('siswa')
                            <a href="{{ route('student.index') }}" wire:navigate
                                class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('student.index*') ? 'bg-white text-[#4A90E2]' : 'text-white hover:bg-blue-500' }}">
                                Beranda
                            </a>
                            <a href="{{ route('student.dashboard') }}" wire:navigate
                                class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('student.dashboard*') ? 'bg-white text-[#4A90E2]' : 'text-white hover:bg-blue-500' }}">
                                Pembelajaran
                            </a>
                            <a href="{{ route('student.subjects') }}" wire:navigate
                                class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('student.subjects*') ? 'bg-white text-[#4A90E2]' : 'text-white hover:bg-blue-500' }}">
                                Materi
                            </a>
                            <a href="{{ route('student.quizzes') }}" wire:navigate
                                class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('student.quiz*') ? 'bg-white text-[#4A90E2]' : 'text-white hover:bg-blue-500' }}">
                                Kuis
                            </a>
                            <a href="{{ route('student.tasks') }}" wire:navigate
                                class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('student.tasks*') ? 'bg-white text-[#4A90E2]' : 'text-white hover:bg-blue-500' }}">
                                Tugas
                            </a>
                            <a href="{{ route('student.games') }}" wire:navigate
                                class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('student.games*') ? 'bg-white text-[#4A90E2]' : 'text-white hover:bg-blue-500' }}">
                                Game Edukatif
                            </a>
                            <a href="{{ route('student.ranking') }}" wire:navigate
                                class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('student.ranking*') ? 'bg-white text-[#4A90E2]' : 'text-white hover:bg-blue-500' }}">
                                Peringkat
                            </a>
                        @endrole

                        @role('guru')
                            <a href="{{ route('teacher.dashboard') }}" wire:navigate
                                class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('teacher.dashboard*') ? 'bg-white text-[#4A90E2]' : 'text-white hover:bg-blue-500' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('teacher.materials') }}" wire:navigate
                                class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('teacher.materials*') ? 'bg-white text-[#4A90E2]' : 'text-white hover:bg-blue-500' }}">
                                Upload Materi
                            </a>
                            <a href="{{ route('teacher.quizzes') }}" wire:navigate
                                class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('teacher.quizzes*') ? 'bg-white text-[#4A90E2]' : 'text-white hover:bg-blue-500' }}">
                                Buat Kuis
                            </a>
                            <a href="{{ route('teacher.about-us') }}" wire:navigate
                                class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('teacher.about-us*') ? 'bg-white text-[#4A90E2]' : 'text-white hover:bg-blue-500' }}">
                                About Us
                            </a>
                        @endrole

                        @role('admin')
                            <a href="{{ route('admin.dashboard') }}" wire:navigate
                                class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.dashboard*') ? 'bg-white text-[#4A90E2]' : 'text-white hover:bg-blue-500' }}">
                                <i class="mr-2 fas fa-tachometer-alt"></i>
                                Dashboard Admin
                            </a>
                        @endrole
                    </nav>
                </div>
            @endauth

            <!-- Right Actions (Desktop) -->
            <div class="items-center flex-shrink-0 hidden space-x-3 lg:flex">
                @auth
                    @role('siswa')
                        @livewire('student.notification-dropdown')
                    @endrole
                    @role('guru')
                        {{-- @livewire('teacher.notification-dropdown') --}}
                    @endrole
                    <x-ui.profile-dropdown />
                @else
                    <a href="{{ route('admin.login') }}" wire:navigate
                        class="bg-white text-[#4A90E2] px-4 py-2 rounded-lg font-semibold hover:bg-gray-200 transition-colors">
                        Admin
                    </a>
                    <a href="{{ route('register') }}" wire:navigate
                        class="bg-white text-[#4A90E2] px-4 py-2 rounded-lg font-semibold hover:bg-gray-200 transition-colors">
                        Daftar
                    </a>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <div class="flex space-x-2 lg:hidden">
                @role('siswa')
                    @livewire('student.notification-dropdown', ['unreadCount' => auth()->user()->unreadNotifications->count()])
                @endrole

                <button @click="mobileMenuOpen = !mobileMenuOpen"
                    class="p-2 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                    <span class="sr-only">Buka menu</span>
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        </div>


    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen" x-cloak x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1" @click.away="mobileMenuOpen = false"
        class="lg:hidden border-t border-blue-400/30 bg-[#4A90E2]">

        <div class="px-4 py-3 space-y-1">
            @auth
                @role('siswa')
                    <a href="{{ route('student.index') }}" wire:navigate @click="mobileMenuOpen = false"
                        class="block px-3 py-2 rounded-md text-base font-medium transition-colors {{ request()->routeIs('student.index*') ? 'bg-white text-[#4A90E2]' : 'text-white hover:bg-blue-500' }}">
                        Beranda
                    </a>
                    <a href="{{ route('student.dashboard') }}" wire:navigate @click="mobileMenuOpen = false"
                        class="block px-3 py-2 rounded-md text-base font-medium transition-colors {{ request()->routeIs('student.dashboard*') ? 'bg-white text-[#4A90E2]' : 'text-white hover:bg-blue-500' }}">
                        Pembelajaran
                    </a>
                    <a href="{{ route('student.subjects') }}" wire:navigate @click="mobileMenuOpen = false"
                        class="block px-3 py-2 rounded-md text-base font-medium transition-colors {{ request()->routeIs('student.subjects*') ? 'bg-white text-[#4A90E2]' : 'text-white hover:bg-blue-500' }}">
                        Materi
                    </a>
                    <a href="{{ route('student.quizzes') }}" wire:navigate @click="mobileMenuOpen = false"
                        class="block px-3 py-2 rounded-md text-base font-medium transition-colors {{ request()->routeIs('student.quiz*') ? 'bg-white text-[#4A90E2]' : 'text-white hover:bg-blue-500' }}">
                        Kuis
                    </a>
                    <a href="{{ route('student.tasks') }}" wire:navigate @click="mobileMenuOpen = false"
                        class="block px-3 py-2 rounded-md text-base font-medium transition-colors {{ request()->routeIs('student.tasks*') ? 'bg-white text-[#4A90E2]' : 'text-white hover:bg-blue-500' }}">
                        Tugas
                    </a>
                    <a href="{{ route('student.games') }}" wire:navigate @click="mobileMenuOpen = false"
                        class="block px-3 py-2 rounded-md text-base font-medium transition-colors {{ request()->routeIs('student.games*') ? 'bg-white text-[#4A90E2]' : 'text-white hover:bg-blue-500' }}">
                        Game Edukatif
                    </a>
                    <a href="{{ route('student.ranking') }}" wire:navigate @click="mobileMenuOpen = false"
                        class="block px-3 py-2 rounded-md text-base font-medium transition-colors {{ request()->routeIs('student.ranking*') ? 'bg-white text-[#4A90E2]' : 'text-white hover:bg-blue-500' }}">
                        Peringkat
                    </a>

                    <!-- Mobile Actions for Students -->
                    <div class="pt-3 mt-3 space-y-2 border-t border-blue-400/30">

                        <div class="pt-2">
                            <x-ui.profile-dropdown />
                        </div>
                    </div>
                @endrole

                @role('guru')
                    <a href="{{ route('teacher.dashboard') }}" wire:navigate @click="mobileMenuOpen = false"
                        class="block px-3 py-2 rounded-md text-base font-medium transition-colors {{ request()->routeIs('teacher.dashboard*') ? 'bg-white text-[#4A90E2]' : 'text-white hover:bg-blue-500' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('teacher.materials') }}" wire:navigate @click="mobileMenuOpen = false"
                        class="block px-3 py-2 rounded-md text-base font-medium transition-colors {{ request()->routeIs('teacher.materials*') ? 'bg-white text-[#4A90E2]' : 'text-white hover:bg-blue-500' }}">
                        Upload Materi
                    </a>
                    <a href="{{ route('teacher.quizzes') }}" wire:navigate @click="mobileMenuOpen = false"
                        class="block px-3 py-2 rounded-md text-base font-medium transition-colors {{ request()->routeIs('teacher.quizzes*') ? 'bg-white text-[#4A90E2]' : 'text-white hover:bg-blue-500' }}">
                        Buat Kuis
                    </a>
                    <a href="{{ route('teacher.about-us') }}" wire:navigate @click="mobileMenuOpen = false"
                        class="block px-3 py-2 rounded-md text-base font-medium transition-colors {{ request()->routeIs('teacher.about-us*') ? 'bg-white text-[#4A90E2]' : 'text-white hover:bg-blue-500' }}">
                        About Us
                    </a>

                    <!-- Mobile Actions for Teachers -->
                    <div class="pt-3 mt-3 space-y-2 border-t border-blue-400/30">
                        @livewire('teacher.notification-dropdown')
                        <div class="pt-2">
                            <x-ui.profile-dropdown />
                        </div>
                    </div>
                @endrole

                @role('admin')
                    <a href="{{ route('admin.dashboard') }}" wire:navigate @click="mobileMenuOpen = false"
                        class="block px-3 py-2 rounded-md text-base font-medium transition-colors {{ request()->routeIs('admin.dashboard*') ? 'bg-white text-[#4A90E2]' : 'text-white hover:bg-blue-500' }}">
                        <i class="mr-2 fas fa-tachometer-alt"></i>
                        Dashboard Admin
                    </a>

                    <!-- Mobile Actions for Admin -->
                    <div class="pt-3 mt-3 border-t border-blue-400/30">
                        <x-ui.profile-dropdown />
                    </div>
                @endrole
            @else
                <!-- Guest Mobile Menu -->
                <div class="space-y-2">
                    <a href="{{ route('admin.login') }}" wire:navigate @click="mobileMenuOpen = false"
                        class="block px-3 py-2 text-base font-medium text-white transition-colors rounded-md hover:bg-blue-500">
                        Admin
                    </a>
                    <a href="{{ route('register') }}" wire:navigate @click="mobileMenuOpen = false"
                        class="block px-3 py-2 text-base font-medium text-white transition-colors rounded-md hover:bg-blue-500">
                        Daftar
                    </a>
                </div>
            @endauth
        </div>
    </div>
</header>
