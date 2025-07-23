<header
    class="bg-[#4A90E2] text-white py-3 px-4 sm:px-6 flex justify-between items-center shadow-md w-full
    x-data="{ mobileMenuOpen: false, profileDropdownOpen: false }">

    <x-ui.logo-nav />



    <div class="flex items-center space-x-3">
        @auth

            <nav class="hidden space-x-4 md:flex">

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
                    <a href="{{ route('admin.dashboard') }}" wire:navigate
                        class="border bg-white text-[#4A90E2] px-4 py-2 rounded-lg font-semibold border border-black hover:bg-gray-200 hover:text-gray-600 transition-colors duration-200">
                        <i class="text-lg fas fa-tachometer-alt"></i>
                        Dashboard Admin
                    </a>
                @endrole
            </nav>

            <x-ui.profile-dropdown />

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
                    <a href="{{ route('admin.dashboard') }}" x-show="mobileMenuOpen"
                        x-transition:enter="transition ease-out duration-300 transform delay-75"
                        x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0"
                        class="w-full px-3 py-2 font-semibold text-white transition-colors duration-200 rounded-md hover:text-gray-200 hover:bg-blue-600"
                        wire:navigate>Dashboard Admin</a>
                @endrole
            </nav>

            {{-- Username dan Logout di Mobile dengan Animation --}}
            <x-ui.profile-dropdown />
        </div>
    @endauth
</header>
