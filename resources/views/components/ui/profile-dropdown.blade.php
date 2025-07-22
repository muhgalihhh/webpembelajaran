<div x-data="{ profileDropdownOpen: false }" @click.away="profileDropdownOpen = false" class="relative z-40"> {{-- Added z-40 here --}}
    {{-- Profile Button --}}
    <button @click="profileDropdownOpen = !profileDropdownOpen"
        class="flex items-center px-3 py-2 space-x-2 text-lg font-semibold text-white transition-all duration-200 rounded-lg bg-blue-950 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-300">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
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
                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
