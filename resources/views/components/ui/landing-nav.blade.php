<header
    class="bg-[#4A90E2] text-white py-3 px-6 flex justify-between items-center rounded-b-lg shadow-md w-full max-w-7xl mx-auto relative z-20"
    x-data="{ mobileMenuOpen: false, profileDropdownOpen: false }">

    <div class="text-xl font-bold">Media Pembelajaran Digital</div>

    <div class="flex items-center space-x-3">
        @auth
            {{-- Navigasi Utama (Desktop - Pengguna yang Sudah Login) --}}
            <nav class="hidden space-x-4 md:flex">
                {{-- Tautan untuk Siswa --}}
                @role('siswa')
                    <a href=""
                        class="px-2 py-1 font-semibold text-black transition-colors duration-200 bg-white border border-black rounded-md hover:text-gray-600"
                        wire:navigate>Materi Pembelajaran</a>
                    <a href=""
                        class="px-2 py-1 font-semibold text-black transition-colors duration-200 bg-white border border-black rounded-md hover:text-gray-600"
                        wire:navigate>Kuis/Quis</a>
                    <a href="#"
                        class="px-2 py-1 font-semibold text-black transition-colors duration-200 bg-white border border-black rounded-md hover:text-gray-600"
                        wire:navigate>Referensi Game Edukatif</a>
                    <a href=""
                        class="relative flex items-center justify-center inline-block px-2 py-1 font-semibold text-black transition-colors duration-200 bg-white border border-black rounded-md hover:text-gray-600"
                        wire:navigate>
                        <i class="text-2xl fas fa-bell"></i>
                        <span
                            class="absolute top-0 right-0 inline-flex items-center justify-center w-3 h-3 text-xs font-bold text-white bg-red-500 rounded-full animate-pulse">!</span>
                    </a>
                @endrole

                {{-- Tautan untuk Guru --}}
                @role('guru')
                    <a href=""
                        class="px-2 py-1 font-semibold text-black transition-colors duration-200 bg-white border border-black rounded-md hover:text-gray-600"
                        wire:navigate>Dashboard Guru</a>
                    <a href=""
                        class="px-2 py-1 font-semibold text-black transition-colors duration-200 bg-white border border-black rounded-md hover:text-gray-600"
                        wire:navigate>Upload Materi</a>
                    <a href=""
                        class="px-2 py-1 font-semibold text-black transition-colors duration-200 bg-white border border-black rounded-md hover:text-gray-600"
                        wire:navigate>Buat Kuis</a>
                    <a href=""
                        class="px-2 py-1 font-semibold text-black transition-colors duration-200 bg-white border border-black rounded-md hover:text-gray-600"
                        wire:navigate>Cek Peringkat</a>
                @endrole

                {{-- Tautan untuk Admin --}}
                @role('admin')
                    <a href=""
                        class="px-2 py-1 font-semibold text-black transition-colors duration-200 bg-white border border-black rounded-md hover:text-gray-600"
                        wire:navigate>Dashboard Admin</a>
                @endrole
            </nav>

            {{-- Profile Dropdown (Desktop - Pengguna yang Sudah Login) --}}
            <div class="relative items-center hidden md:flex">
                {{-- Profile Button --}}
                <button @click="profileDropdownOpen = !profileDropdownOpen" @click.away="profileDropdownOpen = false"
                    class="flex items-center px-3 py-2 space-x-2 text-lg font-semibold transition-all duration-200 rounded-lg bg-blue-950 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 3a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    <span>{{ Auth::user()->name }}</span>
                    {{-- Dropdown Arrow --}}
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': profileDropdownOpen }"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                {{-- Dropdown Menu --}}
                <div x-show="profileDropdownOpen" x-cloak x-transition:enter="transition ease-out duration-200 transform"
                    x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150 transform"
                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                    x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                    class="absolute right-0 z-50 w-56 mt-2 bg-white border border-gray-200 rounded-lg shadow-lg top-full">

                    {{-- User Info Section --}}
                    <div class="px-4 py-3 border-b border-gray-100">
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                                @hasrole('siswa')
                                    <div class="text-xs font-medium text-blue-600">Siswa</div>
                                @endhasrole
                                @hasrole('guru')
                                    <div class="text-xs font-medium text-green-600">Guru</div>
                                @endhasrole
                                @hasrole('admin')
                                    <div class="text-xs font-medium text-purple-600">Admin</div>
                                @endhasrole
                            </div>
                        </div>
                    </div>

                    {{-- Menu Items --}}
                    <div class="py-2">
                        <a href="#"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 transition-colors duration-200 hover:bg-gray-50 hover:text-gray-900">
                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                </path>
                            </svg>
                            Lihat Profile
                        </a>

                        <div class="my-1 border-t border-gray-100"></div>

                        <button @click.prevent="$dispatch('open-logout-modal')"
                            class="flex items-center w-full px-4 py-2 text-sm text-red-600 transition-colors duration-200 hover:bg-red-50 hover:text-red-700">
                            <svg class="w-4 h-4 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                            Logout
                        </button>
                    </div>
                </div>
            </div>

            {{-- Hamburger menu icon untuk Mobile (dengan Alpine.js) --}}
            <div class="md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                    class="p-1 text-white transition-transform duration-300 focus:outline-none"
                    :class="{ 'rotate-90': mobileMenuOpen }">
                    {{-- Animated Hamburger Icon --}}
                    <svg class="w-6 h-6 transition-all duration-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        {{-- Menggunakan template literal untuk mengubah path berdasarkan state --}}
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            :d="mobileMenuOpen ? 'M6 18L18 6M6 6l12 12' : 'M4 6h16M4 12h16M4 18h16'">
                        </path>
                    </svg>
                </button>
            </div>
        @else
            {{-- Tombol ADMIN (Untuk Pengguna Belum Login) --}}
            <a href="{{ route('admin.login') }}" wire:navigate
                class="bg-white text-[#4A90E2] px-4 py-2 rounded-lg font-semibold flex items-center space-x-1 hover:bg-blue-500 hover:text-white transition-colors duration-200">
                <i class="text-lg fas fa-user-shield"></i>
                <span>ADMIN</span>
            </a>
            {{-- Tombol Daftar (Untuk Pengguna Belum Login) --}}
            <a href="{{ route('register') }}"
                class="bg-white text-[#4A90E2] px-4 py-2 rounded-lg font-semibold border border-[#4A90E2] hover:bg-[#4A90E2] hover:text-white transition-colors duration-200"
                wire:navigate>
                Daftar
            </a>
        @endauth
    </div>

    {{-- Mobile Menu Dropdown dengan Alpine.js Animations --}}
    @auth
        <div x-show="mobileMenuOpen" x-cloak x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 -translate-y-4 scale-95" @click.away="mobileMenuOpen = false"
            class="md:hidden absolute top-full left-0 w-full bg-[#4A90E2] shadow-lg rounded-b-lg pb-4 z-50 min-h-[200px]">

            <nav class="flex flex-col items-start px-6 py-4 space-y-3">
                {{-- Menu untuk Siswa (Mobile) dengan Staggered Animation --}}
                @role('siswa')
                    <a href="" x-show="mobileMenuOpen"
                        x-transition:enter="transition ease-out duration-300 transform delay-75"
                        x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0"
                        class="block w-full px-4 py-3 font-semibold text-white transition-colors duration-200 rounded-md hover:text-gray-200 hover:bg-blue-600"
                        wire:navigate>Dashboard Siswa</a>

                    <a href="" x-show="mobileMenuOpen"
                        x-transition:enter="transition ease-out duration-300 transform delay-100"
                        x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0"
                        class="w-full px-3 py-2 font-semibold text-white transition-colors duration-200 rounded-md hover:text-gray-200 hover:bg-blue-600"
                        wire:navigate>Materi Pembelajaran</a>

                    <a href="" x-show="mobileMenuOpen"
                        x-transition:enter="transition ease-out duration-300 transform delay-125"
                        x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0"
                        class="w-full px-3 py-2 font-semibold text-white transition-colors duration-200 rounded-md hover:text-gray-200 hover:bg-blue-600"
                        wire:navigate>Kuis/Quis</a>

                    <a href="#" x-show="mobileMenuOpen"
                        x-transition:enter="transition ease-out duration-300 transform delay-150"
                        x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0"
                        class="flex items-center w-full px-3 py-2 text-white transition-colors duration-200 rounded-md hover:text-gray-200 hover:bg-blue-600"
                        wire:navigate>
                        <i class="mr-2 text-xl fas fa-bell"></i>
                        Notifikasi
                        <span class="px-2 py-1 ml-auto text-xs text-white bg-red-500 rounded-full animate-pulse">!</span>
                    </a>
                @endrole

                {{-- Menu untuk Guru (Mobile) dengan Staggered Animation --}}
                @role('guru')
                    <a href="" x-show="mobileMenuOpen"
                        x-transition:enter="transition ease-out duration-300 transform delay-75"
                        x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0"
                        class="w-full px-3 py-2 font-semibold text-white transition-colors duration-200 rounded-md hover:text-gray-200 hover:bg-blue-600"
                        wire:navigate>Dashboard Guru</a>

                    <a href="" x-show="mobileMenuOpen"
                        x-transition:enter="transition ease-out duration-300 transform delay-100"
                        x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0"
                        class="w-full px-3 py-2 font-semibold text-white transition-colors duration-200 rounded-md hover:text-gray-200 hover:bg-blue-600"
                        wire:navigate>Upload Materi</a>

                    <a href="" x-show="mobileMenuOpen"
                        x-transition:enter="transition ease-out duration-300 transform delay-125"
                        x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0"
                        class="w-full px-3 py-2 font-semibold text-white transition-colors duration-200 rounded-md hover:text-gray-200 hover:bg-blue-600"
                        wire:navigate>Buat Kuis</a>

                    <a href="" x-show="mobileMenuOpen"
                        x-transition:enter="transition ease-out duration-300 transform delay-150"
                        x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0"
                        class="w-full px-3 py-2 font-semibold text-white transition-colors duration-200 rounded-md hover:text-gray-200 hover:bg-blue-600"
                        wire:navigate>Cek Peringkat</a>
                @endrole

                {{-- Menu untuk Admin (Mobile) dengan Staggered Animation --}}
                @role('admin')
                    <a href="" x-show="mobileMenuOpen"
                        x-transition:enter="transition ease-out duration-300 transform delay-75"
                        x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0"
                        class="w-full px-3 py-2 font-semibold text-white transition-colors duration-200 rounded-md hover:text-gray-200 hover:bg-blue-600"
                        wire:navigate>Dashboard Admin</a>
                @endrole
            </nav>

            {{-- Username dan Logout di Mobile dengan Animation --}}
            <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300 transform delay-200"
                x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                class="px-6 pt-2 mt-2 border-t border-blue-700">

                <div class="flex items-center py-1 mb-2 space-x-2 text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 3a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    <span>{{ Auth::user()->name }}</span>
                </div>

                {{-- Tombol Profile (Mobile) --}}
                <a href="#"
                    class="block w-full px-3 py-1 mb-2 text-sm font-semibold text-white transition-colors duration-200 bg-blue-700 rounded-md hover:bg-blue-600">
                    Lihat Profile
                </a>

                {{-- Tombol Logout (Mobile) --}}
                <button @click.prevent="$dispatch('open-logout-modal')"
                    class="px-3 py-1 font-semibold text-white transition-colors duration-200 bg-red-600 rounded-md hover:bg-red-700">
                    Logout
                </button>
            </div>
        </div>
    @endauth
</header>
