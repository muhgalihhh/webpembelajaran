<div x-data="{ profileDropdownOpen: false }" @click.away="profileDropdownOpen = false" class="relative z-50">
    {{-- Tombol Profil (Sesuai gaya asli Anda) --}}
    <button @click="profileDropdownOpen = !profileDropdownOpen"
        class="flex items-center p-2 text-lg font-semibold text-white transition-all duration-200 rounded-lg bg-blue-950 md:px-3 md:py-2 md:space-x-2 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-300">

        {{-- Ikon Profil --}}
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 3a9 9 0 11-18 0 9 9 0 0118 0z">
            </path>
        </svg>

        {{-- Nama Pengguna (tampil di layar medium ke atas) --}}
        <span class="hidden md:inline">{{ Auth::user()->name }}</span>

        {{-- Ikon Panah Dropdown --}}
        <svg class="hidden w-4 h-4 transition-transform duration-200 md:inline"
            :class="{ 'rotate-180': profileDropdownOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    {{-- Menu Dropdown (Kotak yang diubah gayanya) --}}
    <div x-show="profileDropdownOpen" x-cloak x-transition:enter="transition ease-out duration-200 transform"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150 transform"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
        class="absolute right-0 z-50 w-56 mt-2 origin-top-right bg-white border border-black rounded-lg shadow-lg top-full">

        {{-- Bagian Header Dropdown --}}
        <div class="px-4 py-3 border-b border-gray-100">



            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <img class="object-cover w-10 h-10 rounded-full"
                        src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="{{ Auth::user()->name }}">
                </div>
                <div>
                    <div class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>
        </div>

        {{-- Bagian Menu Link --}}
        <div class="py-2">
            {{-- Link ke halaman profil --}}
            @hasrole('admin')
                <a href="{{ route('admin.profile') }}" wire:navigate
                    class="flex items-center px-4 py-2 text-sm text-gray-700 transition-colors duration-200 hover:bg-gray-100 hover:text-gray-900">
                    <i class="w-5 mr-3 text-center text-gray-400 fa-solid fa-user-circle"></i>
                    Lihat Profile
                </a>
            @endhasrole
            @hasrole('guru')
                <a href="{{ route('teacher.profile') }}" wire:navigate
                    class="flex items-center px-4 py-2 text-sm text-gray-700 transition-colors duration-200 hover:bg-gray-100 hover:text-gray-900">
                    <i class="w-5 mr-3 text-center text-gray-400 fa-solid fa-user-circle"></i>
                    Lihat Profile
                </a>
            @endhasrole
            @hasrole('siswa')
                <a href="{{ route('student.profile') }}"
                    class="flex items-center px-4 py-2 text-sm text-gray-700 transition-colors duration-200 hover:bg-gray-100 hover:text-gray-900">
                    <i class="w-5 mr-3 text-center text-gray-400 fa-solid fa-user-circle"></i>
                    Lihat Profile
                </a>
            @endhasrole

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
