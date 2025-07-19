// resources/views/components/ui/landing-nav.blade.php
<header
    class="bg-[#4A90E2] text-white py-3 px-6 flex justify-between items-center rounded-b-lg shadow-md w-full max-w-7xl mx-auto relative z-20"
    x-data="{ mobileMenuOpen: false }">

    <div class="text-xl font-bold">Media Pembelajaran Digital</div>

    <div class="flex items-center space-x-3">
        @auth
            {{-- Navigasi Utama (Desktop - Pengguna yang Sudah Login) --}}
            <nav class="hidden md:flex space-x-4">
                {{-- Tautan untuk Siswa --}}
                @role('siswa')
                    <a href=""
                        class="hover:text-gray-600 text-black bg-white py-1 px-2 border border-black font-semibold rounded-md transition-colors duration-200"
                        wire:navigate>Materi Pembelajaran</a>
                    <a href=""
                        class="hover:text-gray-600 text-black bg-white py-1 px-2 border border-black font-semibold rounded-md transition-colors duration-200"
                        wire:navigate>Kuis/Quis</a>
                    <a href="#"
                        class="hover:text-gray-600 text-black bg-white py-1 px-2 border border-black font-semibold rounded-md transition-colors duration-200"
                        wire:navigate>Referensi Game Edukatif</a>
                    <a href=""
                        class="relative inline-block hover:text-gray-600 text-black bg-white py-1 px-2 border border-black font-semibold rounded-md flex items-center justify-center transition-colors duration-200"
                        wire:navigate>
                        <i class="fas fa-bell text-2xl"></i>
                        <span
                            class="absolute top-0 right-0 inline-flex items-center justify-center w-3 h-3 bg-red-500 text-white text-xs font-bold rounded-full animate-pulse">!</span>
                    </a>
                @endrole

                {{-- Tautan untuk Guru --}}
                @role('guru')
                    <a href=""
                        class="hover:text-gray-600 text-black bg-white py-1 px-2 border border-black font-semibold rounded-md transition-colors duration-200"
                        wire:navigate>Dashboard Guru</a>
                    <a href=""
                        class="hover:text-gray-600 text-black bg-white py-1 px-2 border border-black font-semibold rounded-md transition-colors duration-200"
                        wire:navigate>Upload Materi</a>
                    <a href=""
                        class="hover:text-gray-600 text-black bg-white py-1 px-2 border border-black font-semibold rounded-md transition-colors duration-200"
                        wire:navigate>Buat Kuis</a>
                    <a href=""
                        class="hover:text-gray-600 text-black bg-white py-1 px-2 border border-black font-semibold rounded-md transition-colors duration-200"
                        wire:navigate>Cek Peringkat</a>
                @endrole

                {{-- Tautan untuk Admin --}}
                @role('admin')
                    <a href=""
                        class="hover:text-gray-600 text-black bg-white py-1 px-2 border border-black font-semibold rounded-md transition-colors duration-200"
                        wire:navigate>Dashboard Admin</a>
                    <a href=""
                        class="hover:text-gray-600 text-black bg-white py-1 px-2 border border-black font-semibold rounded-md transition-colors duration-200"
                        wire:navigate>Kelola Guru</a>
                    <a href=""
                        class="hover:text-gray-600 text-black bg-white py-1 px-2 border border-black font-semibold rounded-md transition-colors duration-200"
                        wire:navigate>Kelola Siswa</a>
                    <a href=""
                        class="hover:text-gray-600 text-black bg-white py-1 px-2 border border-black font-semibold rounded-md transition-colors duration-200"
                        wire:navigate>Kelola Mapel</a>
                @endrole
            </nav>

            {{-- Bagian Username dan Logout (Desktop - Pengguna yang Sudah Login) --}}
            <div class="hidden md:flex items-center space-x-2">
                <div
                    class="text-lg font-semibold items-center space-x-2 bg-blue-950 py-1 px-2 rounded-md hover:bg-blue-800 transition-colors duration-200 flex">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 3a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    <span>{{ Auth::user()->name }}</span>
                </div>

                {{-- Tombol Logout (Desktop) --}}
                <button wire:click="confirmLogout"
                    class="bg-red-600 text-white px-3 py-1 rounded-md font-semibold hover:bg-red-700 transition-colors duration-200">
                    Logout
                </button>
            </div>

            {{-- Hamburger menu icon untuk Mobile (dengan Alpine.js) --}}
            <div class="md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                    class="text-white focus:outline-none p-1 transition-transform duration-300"
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
                <i class="fas fa-user-shield text-lg"></i>
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
                        class="text-white hover:text-gray-200 hover:bg-blue-600 w-full py-3 px-4 rounded-md font-semibold transition-colors duration-200 block"
                        wire:navigate>Dashboard Siswa</a>

                    <a href="" x-show="mobileMenuOpen"
                        x-transition:enter="transition ease-out duration-300 transform delay-100"
                        x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0"
                        class="text-white hover:text-gray-200 hover:bg-blue-600 w-full py-2 px-3 rounded-md font-semibold transition-colors duration-200"
                        wire:navigate>Materi Pembelajaran</a>

                    <a href="" x-show="mobileMenuOpen"
                        x-transition:enter="transition ease-out duration-300 transform delay-125"
                        x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0"
                        class="text-white hover:text-gray-200 hover:bg-blue-600 w-full py-2 px-3 rounded-md font-semibold transition-colors duration-200"
                        wire:navigate>Kuis/Quis</a>

                    <a href="#" x-show="mobileMenuOpen"
                        x-transition:enter="transition ease-out duration-300 transform delay-150"
                        x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0"
                        class="text-white hover:text-gray-200 hover:bg-blue-600 w-full py-2 px-3 rounded-md flex items-center transition-colors duration-200"
                        wire:navigate>
                        <i class="fas fa-bell text-xl mr-2"></i>
                        Notifikasi
                        <span class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-1 animate-pulse">!</span>
                    </a>
                @endrole

                {{-- Menu untuk Guru (Mobile) dengan Staggered Animation --}}
                @role('guru')
                    <a href="" x-show="mobileMenuOpen"
                        x-transition:enter="transition ease-out duration-300 transform delay-75"
                        x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0"
                        class="text-white hover:text-gray-200 hover:bg-blue-600 w-full py-2 px-3 rounded-md font-semibold transition-colors duration-200"
                        wire:navigate>Dashboard Guru</a>

                    <a href="" x-show="mobileMenuOpen"
                        x-transition:enter="transition ease-out duration-300 transform delay-100"
                        x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0"
                        class="text-white hover:text-gray-200 hover:bg-blue-600 w-full py-2 px-3 rounded-md font-semibold transition-colors duration-200"
                        wire:navigate>Upload Materi</a>

                    <a href="" x-show="mobileMenuOpen"
                        x-transition:enter="transition ease-out duration-300 transform delay-125"
                        x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0"
                        class="text-white hover:text-gray-200 hover:bg-blue-600 w-full py-2 px-3 rounded-md font-semibold transition-colors duration-200"
                        wire:navigate>Buat Kuis</a>

                    <a href="" x-show="mobileMenuOpen"
                        x-transition:enter="transition ease-out duration-300 transform delay-150"
                        x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0"
                        class="text-white hover:text-gray-200 hover:bg-blue-600 w-full py-2 px-3 rounded-md font-semibold transition-colors duration-200"
                        wire:navigate>Cek Peringkat</a>
                @endrole

                {{-- Menu untuk Admin (Mobile) dengan Staggered Animation --}}
                @role('admin')
                    <a href="" x-show="mobileMenuOpen"
                        x-transition:enter="transition ease-out duration-300 transform delay-75"
                        x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0"
                        class="text-white hover:text-gray-200 hover:bg-blue-600 w-full py-2 px-3 rounded-md font-semibold transition-colors duration-200"
                        wire:navigate>Dashboard Admin</a>

                    <a href="" x-show="mobileMenuOpen"
                        x-transition:enter="transition ease-out duration-300 transform delay-100"
                        x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0"
                        class="text-white hover:text-gray-200 hover:bg-blue-600 w-full py-2 px-3 rounded-md font-semibold transition-colors duration-200"
                        wire:navigate>Kelola Guru</a>

                    <a href="" x-show="mobileMenuOpen"
                        x-transition:enter="transition ease-out duration-300 transform delay-125"
                        x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0"
                        class="text-white hover:text-gray-200 hover:bg-blue-600 w-full py-2 px-3 rounded-md font-semibold transition-colors duration-200"
                        wire:navigate>Kelola Siswa</a>

                    <a href="" x-show="mobileMenuOpen"
                        x-transition:enter="transition ease-out duration-300 transform delay-150"
                        x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0"
                        class="text-white hover:text-gray-200 hover:bg-blue-600 w-full py-2 px-3 rounded-md font-semibold transition-colors duration-200"
                        wire:navigate>Kelola Mapel</a>
                @endrole
            </nav>

            {{-- Username dan Logout di Mobile dengan Animation --}}
            <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300 transform delay-200"
                x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                class="px-6 pt-2 border-t border-blue-700 mt-2">

                <div class="flex items-center space-x-2 text-white mb-2 py-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 3a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    <span>{{ Auth::user()->name }}</span>
                </div>

                {{-- Tombol Logout (Mobile) --}}
                <button wire:click="confirmLogout"
                    class="bg-red-600 text-white px-3 py-1 rounded-md font-semibold hover:bg-red-700 transition-colors duration-200">
                    Logout
                </button>
            </div>
        </div>
    @endauth
</header>
