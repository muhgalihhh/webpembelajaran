<header x-data="{ mobileMenuOpen: false, profileDropdownOpen: false }"
    class="bg-[#4A90E2] text-white py-3 px-4 sm:px-6 flex justify-between items-center shadow-md w-full sticky top-0 z-50">
    <x-ui.logo-nav />
    <div class="flex items-center space-x-3">
        @auth
            <nav class="hidden space-x-4 md:flex">
                @role('siswa')
                    <a href="{{ route('student.index') }}" wire:navigate
                        class="px-3 py-2 text-black border rounded-md hover:text-gray-200 {{ request()->routeIs('student.index*') ? 'bg-blue-200 font-bold' : 'bg-white' }}">Beranda</a>
                    <a href="{{ route('student.dashboard') }}" wire:navigate
                        class="px-3 py-2 text-black  border rounded-md hover:text-gray-200 {{ request()->routeIs('student.dashboard*') ? 'bg-blue-200 font-bold' : 'bg-white' }}">Pembelajaran</a>
                    <a href="{{ route('student.subjects') }}" wire:navigate
                        class="px-3 py-2 text-black  border rounded-md hover:text-gray-200 {{ request()->routeIs('student.subjects*') ? 'bg-blue-200 font-bold' : 'bg-white' }}">Materi</a>
                    <a href="{{ route('student.quizzes') }}" wire:navigate
                        class="px-3 py-2 text-black border rounded-md hover:text-gray-200 {{ request()->routeIs('student.quiz*') ? 'bg-blue-200 font-bold' : 'bg-white' }}">Kuis</a>
                    <a href="{{ route('student.tasks') }} " wire:navigate
                        class="px-3 py-2 text-black border rounded-md hover:text-gray-200 {{ request()->routeIs('student.tasks*') ? 'bg-blue-200 font-bold' : 'bg-white' }}">Tugas</a>
                    <a href="{{ route('student.games') }}" wire:navigate
                        class="px-3 py-2 text-black  border rounded-md hover:text-gray-200 {{ request()->routeIs('student.games*') ? 'bg-blue-200 font-bold' : 'bg-white' }}">Game
                        Edukatif</a>

                    <a href="{{ route('student.ranking') }}" wire:navigate
                        class="px-3 py-2 text-black  border rounded-md hover:text-gray-200 {{ request()->routeIs('student.ranking*') ? 'bg-blue-200 font-bold' : 'bg-white' }}">Peringkat</a>
                @endrole

                @role('guru')
                    <a href="{{ route('teacher.dashboard') }}" wire:navigate
                        class="px-3 py-2 text-black bg-white border rounded-md hover:text-gray-200">Dashboard</a>
                    <a href="{{ route('teacher.materials') }}" wire:navigate
                        class="px-3 py-2 text-black bg-white border rounded-md hover:text-gray-200">Upload Materi</a>
                    <a href="{{ route('teacher.quizzes') }}" wire:navigate
                        class="px-3 py-2 text-black bg-white border rounded-md hover:text-gray-200">Buat Kuis</a>
                    <a href="{{ route('teacher.about-us') }}" wire:navigate
                        class="px-3 py-2 text-black bg-white border rounded-md hover:text-gray-200">About Us</a>
                @endrole

                @role('admin')
                    <a href="{{ route('admin.dashboard') }}" wire:navigate
                        class="px-3 py-2 text-black bg-white border rounded-md hover:text-gray-200 {{ request()->routeIs('admin.dashboard*') ? 'bg-blue-200 font-bold' : 'bg-white' }}">
                        <i class="mr-2 fas fa-tachometer-alt"></i>
                        Dashboard Admin
                    </a>
                @endrole
            </nav>
            @role('siswa')
                @livewire('student.notification-dropdown', ['unreadCount' => auth()->user()->unreadNotifications->count()])
            @endrole
            <x-ui.profile-dropdown />
            <div class="md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                    class="p-2 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                    <span class="sr-only">Buka menu</span>
                    {{-- Ikon berubah dari hamburger ke 'X' saat menu terbuka --}}
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                        </path>
                    </svg>
                    <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        @else
            <a href="{{ route('admin.login') }}" wire:navigate
                class="hidden sm:inline-block bg-white text-[#4A90E2] px-4 py-2 rounded-lg font-semibold hover:bg-gray-200">
                Admin
            </a>
            <a href="{{ route('register') }}" wire:navigate
                class="bg-white text-[#4A90E2] px-4 py-2 rounded-lg font-semibold hover:bg-gray-200">
                Daftar
            </a>
        @endauth
    </div>

    @auth
        <div x-show="mobileMenuOpen" x-cloak x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95" @click.away="mobileMenuOpen = false"
            class="absolute top-full left-0 w-full bg-[#4A90E2] shadow-lg rounded-b-lg p-4 md:hidden z-50">

            <nav class="flex flex-col space-y-2">
                @role('siswa')
                    <a href="{{ route('student.index') }}" wire:navigate
                        class="px-3 py-2 text-black border rounded-md hover:text-gray-200 {{ request()->routeIs('student.index*') ? 'bg-blue-200 font-bold' : 'bg-white' }}">Beranda</a>
                    <a href="{{ route('student.dashboard') }}" wire:navigate
                        class="px-3 py-2 text-black  border rounded-md hover:text-gray-200 {{ request()->routeIs('student.dashboard*') ? 'bg-blue-200 font-bold' : 'bg-white' }}">Pembelajaran</a>
                    <a href="{{ route('student.subjects') }}" wire:navigate
                        class="px-3 py-2 text-black  border rounded-md hover:text-gray-200 {{ request()->routeIs('student.subjects*') ? 'bg-blue-200 font-bold' : 'bg-white' }}">Materi</a>
                    <a href="{{ route('student.quizzes') }}" wire:navigate
                        class="px-3 py-2 text-black border rounded-md hover:text-gray-200 {{ request()->routeIs('student.quiz*') ? 'bg-blue-200 font-bold' : 'bg-white' }}">Kuis</a>
                    <a href="{{ route('student.tasks') }} " wire:navigate
                        class="px-3 py-2 text-black border rounded-md hover:text-gray-200 {{ request()->routeIs('student.tasks*') ? 'bg-blue-200 font-bold' : 'bg-white' }}">Tugas</a>
                    <a href="{{ route('student.games') }}" wire:navigate
                        class="px-3 py-2 text-black  border rounded-md hover:text-gray-200 {{ request()->routeIs('student.games*') ? 'bg-blue-200 font-bold' : 'bg-white' }}">Game
                        Edukatif</a>
                    <a href="{{ route('student.ranking') }}" wire:navigate
                        class="px-3 py-2 text-black  border rounded-md hover:text-gray-200 {{ request()->routeIs('student.ranking*') ? 'bg-blue-200 font-bold' : 'bg-white' }}">Peringkat</a>
                    @livewire('student.notification-dropdown', ['unreadCount' => auth()->user()->unreadNotifications->count()])
                @endrole
                @role('guru')
                    <a href="{{ route('teacher.dashboard') }}" wire:navigate
                        class="px-3 py-2 text-black bg-white border rounded-md hover:text-gray-200">Dashboard</a>
                    <a href="{{ route('teacher.materials') }}" wire:navigate
                        class="px-3 py-2 text-black bg-white border rounded-md hover:text-gray-200">Upload Materi</a>
                    <a href="{{ route('teacher.quizzes') }}" wire:navigate
                        class="px-3 py-2 text-black bg-white border rounded-md hover:text-gray-200">Buat Kuis</a>
                    <a href="{{ route('teacher.about-us') }}" wire:navigate
                        class="px-3 py-2 text-black bg-white border rounded-md hover:text-gray-200">About Us</a>
                @endrole
                @role('admin')
                    <a href="{{ route('admin.dashboard') }}" wire:navigate @click="mobileMenuOpen = false"
                        class="px-3 py-2 text-black bg-white border rounded-md hover:text-gray-200 {{ request()->routeIs('admin.dashboard*') ? 'bg-blue-200 font-bold' : 'bg-white' }}">Dashboard
                        Admin</a>
                @endrole
            </nav>
        </div>
    @endauth
</header>
