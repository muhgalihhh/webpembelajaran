<header
    class="bg-[#4A90E2] text-white py-3 px-6 flex justify-between items-center rounded-b-lg shadow-md w-full max-w-7xl mx-auto relative z-20"
    x-data="{ mobileMenuOpen: false }">

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

            {{-- Bagian Username dan Logout (Desktop - Pengguna yang Sudah Login) --}}
            <div class="items-center hidden space-x-2 md:flex">
                <div
                    class="flex items-center px-2 py-1 space-x-2 text-lg font-semibold transition-colors duration-200 rounded-md bg-blue-950 hover:bg-blue-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 3a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    <span>{{ Auth::user()->name }}</span>
                </div>

                {{-- Tombol Logout (Desktop) dengan SweetAlert --}}
                <button onclick="handleLogout()"
                    class="px-3 py-1 font-semibold text-white transition-colors duration-200 bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    <i class="mr-1 fas fa-sign-out-alt"></i>
                    Logout
                </button>
            </div>

            {{-- Hamburger menu icon untuk Mobile --}}
            <div class="md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                    class="p-1 text-white transition-transform duration-300 focus:outline-none"
                    :class="{ 'rotate-90': mobileMenuOpen }">
                    <svg class="w-6 h-6 transition-all duration-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            :d="mobileMenuOpen ? 'M6 18L18 6M6 6l12 12' : 'M4 6h16M4 12h16M4 18h16'">
                        </path>
                    </svg>
                </button>
            </div>
        @else
            {{-- Tombol untuk Pengguna Belum Login --}}
            <a href="{{ route('admin.login') }}" wire:navigate
                class="bg-white text-[#4A90E2] px-4 py-2 rounded-lg font-semibold flex items-center space-x-1 hover:bg-blue-500 hover:text-white transition-colors duration-200">
                <i class="text-lg fas fa-user-shield"></i>
                <span>ADMIN</span>
            </a>
            <a href="{{ route('register') }}"
                class="bg-white text-[#4A90E2] px-4 py-2 rounded-lg font-semibold border border-[#4A90E2] hover:bg-[#4A90E2] hover:text-white transition-colors duration-200"
                wire:navigate>
                Daftar
            </a>
        @endauth
    </div>

    {{-- Mobile Menu Dropdown --}}
    @auth
        <div x-show="mobileMenuOpen" x-cloak x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 -translate-y-4 scale-95" @click.away="mobileMenuOpen = false"
            class="md:hidden absolute top-full left-0 w-full bg-[#4A90E2] shadow-lg rounded-b-lg pb-4 z-50 min-h-[200px]">

            <nav class="flex flex-col items-start px-6 py-4 space-y-3">
                {{-- Mobile menu items (same as before) --}}
                @role('siswa')
                    <a href=""
                        class="block w-full px-4 py-3 font-semibold text-white transition-colors duration-200 rounded-md hover:text-gray-200 hover:bg-blue-600"
                        wire:navigate>Dashboard Siswa</a>
                    <a href=""
                        class="w-full px-3 py-2 font-semibold text-white transition-colors duration-200 rounded-md hover:text-gray-200 hover:bg-blue-600"
                        wire:navigate>Materi Pembelajaran</a>
                    <a href=""
                        class="w-full px-3 py-2 font-semibold text-white transition-colors duration-200 rounded-md hover:text-gray-200 hover:bg-blue-600"
                        wire:navigate>Kuis/Quis</a>
                    <a href="#"
                        class="flex items-center w-full px-3 py-2 text-white transition-colors duration-200 rounded-md hover:text-gray-200 hover:bg-blue-600"
                        wire:navigate>
                        <i class="mr-2 text-xl fas fa-bell"></i>
                        Notifikasi
                        <span class="px-2 py-1 ml-auto text-xs text-white bg-red-500 rounded-full animate-pulse">!</span>
                    </a>
                @endrole

                @role('guru')
                    <a href=""
                        class="w-full px-3 py-2 font-semibold text-white transition-colors duration-200 rounded-md hover:text-gray-200 hover:bg-blue-600"
                        wire:navigate>Dashboard Guru</a>
                    <a href=""
                        class="w-full px-3 py-2 font-semibold text-white transition-colors duration-200 rounded-md hover:text-gray-200 hover:bg-blue-600"
                        wire:navigate>Upload Materi</a>
                    <a href=""
                        class="w-full px-3 py-2 font-semibold text-white transition-colors duration-200 rounded-md hover:text-gray-200 hover:bg-blue-600"
                        wire:navigate>Buat Kuis</a>
                    <a href=""
                        class="w-full px-3 py-2 font-semibold text-white transition-colors duration-200 rounded-md hover:text-gray-200 hover:bg-blue-600"
                        wire:navigate>Cek Peringkat</a>
                @endrole

                @role('admin')
                    <a href=""
                        class="w-full px-3 py-2 font-semibold text-white transition-colors duration-200 rounded-md hover:text-gray-200 hover:bg-blue-600"
                        wire:navigate>Dashboard Admin</a>
                @endrole
            </nav>

            {{-- Username dan Logout di Mobile --}}
            <div class="px-6 pt-2 mt-2 border-t border-blue-700">
                <div class="flex items-center py-1 mb-2 space-x-2 text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 3a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    <span>{{ Auth::user()->name }}</span>
                </div>

                {{-- Tombol Logout (Mobile) dengan SweetAlert --}}
                <button onclick="handleLogout()"
                    class="w-full px-3 py-2 font-semibold text-white transition-colors duration-200 bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    <i class="mr-1 fas fa-sign-out-alt"></i>
                    Logout
                </button>
            </div>
        </div>
    @endauth
</header>

{{-- Include Logout Handler Component --}}
@auth
    @livewire('auth.logout-handler')
@endauth

<script>
    // Handle logout with SweetAlert
    function handleLogout() {
        swalLogout(() => {
            // Create and submit logout form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('logout') }}';

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';

            form.appendChild(csrfToken);
            document.body.appendChild(form);
            form.submit();
        });
    }
</script>
