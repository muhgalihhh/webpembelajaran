<div x-data="{ profileDropdownOpen: false, panduanModalOpen: false, role: '{{ Auth::user()->getRoleNames()->first() }}' }"
     @click.away="profileDropdownOpen = false"
     class="relative z-50">

    {{-- Tombol Profil --}}
    <button @click="profileDropdownOpen = !profileDropdownOpen"
        class="flex items-center p-3 text-lg font-semibold text-white transition-all duration-200 rounded-lg bg-blue-950 md:px-3 md:py-2 md:space-x-2 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-300">

        {{-- Ikon Profil --}}
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 3a9 9 0 11-18 0 9 9 0 0118 0z">
            </path>
        </svg>

        <span class="hidden md:inline">{{ Auth::user()->name }}</span>

        {{-- Ikon Panah Dropdown --}}
        <svg class="hidden w-4 h-4 transition-transform duration-200 md:inline"
            :class="{ 'rotate-180': profileDropdownOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    {{-- Menu Dropdown --}}
    <div x-show="profileDropdownOpen" x-cloak
        x-transition:enter="transition ease-out duration-200 transform"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150 transform"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
        class="absolute right-0 z-50 w-56 mt-2 origin-top-right bg-white border border-black rounded-lg shadow-lg top-full">

        {{-- Header --}}
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

        {{-- Menu --}}
        <div class="py-2">
            {{-- Link ke profil --}}
            @hasrole('admin')
                <a href="{{ route('admin.profile') }}" wire:navigate
                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                    <i class="w-5 mr-3 text-gray-400 fa-solid fa-user-circle"></i>
                    Lihat Profile
                </a>
            @endhasrole
            @hasrole('guru')
                <a href="{{ route('teacher.profile') }}" wire:navigate
                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                    <i class="w-5 mr-3 text-gray-400 fa-solid fa-user-circle"></i>
                    Lihat Profile
                </a>
            @endhasrole
            @hasrole('siswa')
                <a href="{{ route('student.profile') }}"
                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                    <i class="w-5 mr-3 text-gray-400 fa-solid fa-user-circle"></i>
                    Lihat Profile
                </a>
            @endhasrole

            {{-- Tombol Panduan --}}
            <button @click="panduanModalOpen = true; profileDropdownOpen = false"
                class="flex items-center w-full px-4 py-2 text-sm text-blue-600 hover:bg-blue-50 hover:text-blue-800">
                <i class="w-5 mr-3 text-blue-400 fa-solid fa-book"></i>
                Panduan
            </button>

            <div class="my-1 border-t border-gray-100"></div>

            {{-- Logout --}}
            <button @click.prevent="$dispatch('open-logout-modal')"
                class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700">
                <svg class="w-4 h-4 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                    </path>
                </svg>
                Logout
            </button>
        </div>
    </div>

    {{-- Modal Panduan --}}
    <div x-show="panduanModalOpen" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-blue-400/40">
        <div class="w-full max-w-lg p-6 bg-white rounded-lg shadow-xl">
            <h2 class="text-lg font-semibold text-gray-800">Panduan Penggunaan</h2>
            <div class="mt-4 text-sm text-gray-600 space-y-2">
                <template x-if="role === 'siswa'">
                    <ul class="list-disc list-inside">
                        <li><b>Beranda</b> - lihat informasi utama</li>
                        <li><b>Pembelajaran</b> - akses materi & aktivitas</li>
                        <li><b>Materi</b> - baca materi yang diberikan guru</li>
                        <li><b>Kuis</b> - ikuti kuis online</li>
                        <li><b>Tugas</b> - kerjakan & upload tugas</li>
                        <li><b>Game Edukatif</b> - main game sambil belajar</li>
                        <li><b>Peringkat</b> - lihat peringkat kelas</li>
                    </ul>
                </template>
                <template x-if="role === 'guru'">
                    <ul class="list-disc list-inside">
                        <li><b>Aktivitas Siswa</b> - pantau aktivitas</li>
                        <li><b>Materi Pembelajaran</b> - unggah & kelola materi</li>
                        <li><b>Kuis / Ujian</b> - buat & kelola kuis</li>
                        <li><b>Tugas</b> - berikan tugas siswa</li>
                        <li><b>Beri Nilai</b> - nilai tugas siswa</li>
                        <li><b>Game Edukatif</b> - integrasikan game</li>
                        <li><b>Nilai Siswa</b> - cek nilai siswa</li>
                        <li><b>About Us</b> - info sistem</li>
                    </ul>
                </template>
                <template x-if="role === 'admin'">
                    <ul class="list-disc list-inside">
                        <li><b>Dashboard</b> - ringkasan data</li>
                        <li><b>Manajemen Guru</b> - kelola akun guru</li>
                        <li><b>Manajemen Siswa</b> - kelola akun siswa</li>
                        <li><b>Manajemen Kelas</b> - buat & edit kelas</li>
                        <li><b>Manajemen Mapel</b> - atur mata pelajaran</li>
                        <li><b>Manajemen Kurikulum</b> - atur kurikulum</li>
                    </ul>
                </template>
            </div>
            <div class="mt-6 text-right">
                <button @click="panduanModalOpen = false"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
