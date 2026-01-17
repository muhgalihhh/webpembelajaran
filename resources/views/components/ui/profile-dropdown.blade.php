<div x-data="{
        profileDropdownOpen: false,
        panduanModalOpen: false,
        role: '{{ Auth::user()->getRoleNames()->first() }}',
        step: 0,
        get steps() {
            if (this.role === 'siswa') {
                return [
                    '📌 <b>Beranda</b> - Di halaman beranda, kamu bisa melihat informasi utama yang diberikan sekolah seperti pengumuman terbaru, ringkasan aktivitas, serta shortcut ke menu penting. Pastikan selalu cek beranda untuk update terbaru.',
                    '📖 <b>Pembelajaran</b> - Bagian ini berisi seluruh materi pembelajaran dari guru. Kamu bisa membuka modul, membaca penjelasan, dan melihat referensi tambahan. Semua aktivitas belajar akan tercatat di sini.',
                    '📚 <b>Materi</b> - Setiap guru dapat mengunggah materi berupa teks, file, maupun link. Kamu bisa mengaksesnya kapan saja sesuai kelas dan mata pelajaran yang diikuti.',
                    '📝 <b>Kuis</b> - Ikuti kuis secara online dengan batas waktu yang ditentukan guru. Setelah selesai, kamu bisa langsung melihat nilai atau menunggu hasil penilaian.',
                    '📂 <b>Tugas</b> - Bagian ini untuk mengunduh tugas dari guru dan mengunggah hasil pengerjaanmu. Pastikan format file sesuai dan kirim sebelum deadline.',
                    '🎮 <b>Game Edukatif</b> - Sediakan hiburan sambil belajar melalui game edukatif interaktif. Game ini dibuat agar belajar lebih menyenangkan.',
                    '🏆 <b>Peringkat</b> - Lihat peringkat berdasarkan nilai tugas, kuis, maupun aktivitas belajar. Gunakan fitur ini untuk memacu semangat dan melihat progresmu dibanding teman-teman.'
                ]
            } else if (this.role === 'guru') {
                return [
                    '👩‍🏫 <b>Aktivitas Siswa</b> - Pantau aktivitas harian siswa seperti login, akses materi, hingga progres tugas dan kuis. Fitur ini memudahkan guru memonitor partisipasi siswa.',
                    '📖 <b>Materi Pembelajaran</b> - Unggah dan kelola materi untuk setiap kelas. Materi bisa berupa teks, PDF, maupun link eksternal, sehingga fleksibel untuk berbagai gaya belajar.',
                    '📝 <b>Kuis / Ujian</b> - Buat, atur, dan kelola soal kuis maupun ujian. Kamu bisa menentukan batas waktu, bobot nilai, serta melihat hasil siswa secara real time.',
                    '📂 <b>Tugas</b> - Berikan instruksi tugas lengkap dengan deadline. Siswa dapat mengunggah hasilnya dalam berbagai format file.',
                    '✅ <b>Beri Nilai</b> - Setelah siswa mengumpulkan tugas, gunakan fitur ini untuk memberi nilai dan catatan langsung. Nilai otomatis tersimpan di database.',
                    '🎮 <b>Game Edukatif</b> - Integrasikan game untuk membuat suasana belajar lebih menyenangkan dan interaktif.',
                    '📊 <b>Nilai Siswa</b> - Pantau rekap nilai semua siswa dalam bentuk tabel dan grafik. Data bisa diekspor bila diperlukan.',
                    'ℹ️ <b>About Us</b> - Informasi tentang sistem dan pengembang aplikasi.'
                ]
            } else if (this.role === 'admin') {
                return [
                    '📊 <b>Dashboard</b> - Ringkasan data utama seperti jumlah guru, siswa, kelas, serta aktivitas terbaru ditampilkan di sini. Admin bisa memantau performa sistem dengan cepat.',
                    '👩‍🏫 <b>Manajemen Guru</b> - Tambah, ubah, atau hapus akun guru. Pastikan data selalu terupdate agar akses tetap aman dan sesuai kebutuhan.',
                    '👨‍🎓 <b>Manajemen Siswa</b> - Kelola data siswa secara lengkap mulai dari biodata, kelas, hingga status aktif/tidak aktif.',
                    '🏫 <b>Manajemen Kelas</b> - Buat kelas baru, ubah informasi kelas, dan tentukan wali kelas. Semua perubahan otomatis tersimpan di database.',
                    '📘 <b>Manajemen Mapel</b> - Atur mata pelajaran sesuai kurikulum yang berlaku. Mapel dapat disesuaikan per tingkat kelas.',
                    '📚 <b>Manajemen Kurikulum</b> - Tentukan struktur kurikulum, mata pelajaran wajib, serta pembagian semester agar sistem pembelajaran lebih terarah.'
                ]
            }
            return []
        }
    }"
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
        class="fixed inset-0 z-50 flex items-center justify-center bg-white/50">
        <div class="relative w-full max-w-lg p-6 bg-white rounded-lg shadow-xl">

            {{-- Tombol Skip --}}
            <button @click="panduanModalOpen = false; step = 0"
                class="absolute top-3 right-3 text-sm text-gray-500 hover:text-gray-700">
                Lewati ✖
            </button>

            <h2 class="text-lg font-semibold text-gray-800">Panduan Penggunaan</h2>

            {{-- Progress bar --}}
            <div class="w-full h-2 mt-2 bg-gray-200 rounded">
                <div class="h-2 bg-blue-500 rounded"
                    :style="`width: ${(step + 1) / steps.length * 100}%`"></div>
            </div>

            <div class="mt-6 text-sm text-gray-700 min-h-[120px] flex items-center">
                <template x-if="steps.length > 0">
                    <p x-html="steps[step]"></p>
                </template>
            </div>

            <div class="flex justify-between mt-6">
                <button @click="step = Math.max(step - 1, 0)"
                    x-bind:disabled="step === 0"
                    class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded hover:bg-gray-200 disabled:opacity-50">
                    Sebelumnya
                </button>

                <div class="text-sm text-gray-500 self-center">
                    <span x-text="step + 1"></span> / <span x-text="steps.length"></span>
                </div>

                <template x-if="step < steps.length - 1">
                    <button @click="step++"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700">
                        Selanjutnya
                    </button>
                </template>
                <template x-if="step === steps.length - 1">
                    <button @click="panduanModalOpen = false; step = 0"
                        class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded hover:bg-green-700">
                        Selesai
                    </button>
                </template>
            </div>
        </div>
    </div>
</div>
