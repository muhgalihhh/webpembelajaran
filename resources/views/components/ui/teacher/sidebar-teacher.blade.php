<div x-show="mobileSidebarOpen" @click="mobileSidebarOpen = false" class="fixed inset-0 z-30 lg:hidden"
    x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
</div>

<aside
    class="fixed inset-y-0 left-0 z-40 flex flex-col flex-shrink-0 space-y-4 overflow-y-auto transition-transform duration-300 ease-in-out bg-gray-200 border-r border-gray-200 shadow-lg lg:relative lg:translate-x-0"
    :class="{
        'w-64': !sidebarCollapsed,
        'lg:w-20 w-64': sidebarCollapsed,
        'translate-x-0': mobileSidebarOpen,
        '-translate-x-full': !mobileSidebarOpen
    }">

    <div class="flex items-center p-2 bg-blue-950" :class="sidebarCollapsed ? 'justify-center' : 'justify-between'">
        <div x-show="!sidebarCollapsed" class="flex flex-col items-start flex-1 space-x-2 text-white">
            <div class="flex items-center w-full mb-2 space-x-2">
                <img src="{{ asset('images/logo sd.png') }}" alt="Logo" class="w-10">
                <div class="flex-1">
                    <span class="text-lg font-bold">GURU</span>
                </div>
                <button @click="mobileSidebarOpen = false"
                    class="p-2 text-white transition-colors duration-200 rounded-full lg:hidden hover:bg-blue-800">
                    <i class="text-sm fas fa-times"></i>
                </button>
            </div>
            <span class="text-sm text-gray-300">{{ Auth::user()->name }}</span>
            <span class="text-xs text-gray-400">{{ Auth::user()->email }}</span>
        </div>

        <div x-show="sidebarCollapsed" class="flex items-center justify-between w-full">
            <img src="{{ asset('images/logo sd.png') }}" alt="Logo" class="w-8 mx-auto">
            <button @click="mobileSidebarOpen = false"
                class="absolute p-2 text-white transition-colors duration-200 rounded-full lg:hidden top-2 right-2 hover:bg-blue-800">
                <i class="text-sm fas fa-times"></i>
            </button>
        </div>

        <button @click="sidebarCollapsed = !sidebarCollapsed"
            class="hidden p-2 transition-colors duration-200 bg-white rounded-full lg:block hover:bg-gray-200"
            title="Toggle Sidebar">
            <i class="transition-transform duration-300 fa-solid fa-chevron-left"
                :class="{ 'rotate-180': sidebarCollapsed }"></i>
        </button>
    </div>

    <nav class="flex-grow px-2">
        <a href="{{ route('teacher.dashboard') }}" wire:navigate title="Aktivitas Siswa"
            @click="$dispatch('close-mobile-sidebar')"
            class="flex items-center p-3 my-2 text-gray-700 transition-all duration-200 border {{ request()->routeIs('teacher.dashboard') ? 'bg-gray-500 text-white font-bold shadow-md' : 'hover:bg-gray-300 hover:shadow-sm' }}"
            :class="{ 'justify-center': sidebarCollapsed }">
            <i class="w-6 text-center fa-solid fa-chart-line"></i>
            <span class="ml-3" x-show="!sidebarCollapsed">Aktivitas Siswa</span>
        </a>

        <div class="mt-6 mb-3" x-show="!sidebarCollapsed">
            <p class="px-3 text-xs font-semibold tracking-wider text-gray-500 uppercase">Menu Utama</p>
        </div>

        <ul class="space-y-1">
            <li>
                <a href="{{ route('teacher.materials') }}" wire:navigate title="Materi Pembelajaran"
                    @click="$dispatch('close-mobile-sidebar')"
                    class="flex items-center p-3 text-gray-700 transition-all duration-200 border {{ request()->routeIs('teacher.materials*') ? 'bg-gray-500 text-white font-bold shadow-md' : 'hover:bg-gray-300 hover:shadow-sm' }}"
                    :class="{ 'justify-center': sidebarCollapsed }">
                    <i class="w-6 text-center fa-solid fa-book-open"></i>
                    <span class="ml-3" x-show="!sidebarCollapsed">Materi Pembelajaran</span>
                </a>
            </li>
            <li>
                <a href="{{ route('teacher.quizzes') }}" wire:navigate title="Kuis / Ujian"
                    @click="$dispatch('close-mobile-sidebar')"
                    class="flex items-center p-3 text-gray-700 transition-all duration-200 border {{ request()->routeIs('teacher.quizzes*') ? 'bg-gray-500 text-white font-bold shadow-md' : 'hover:bg-gray-300 hover:shadow-sm' }}"
                    :class="{ 'justify-center': sidebarCollapsed }">
                    <i class="w-6 text-center fa-solid fa-file-signature"></i>
                    <span class="ml-3" x-show="!sidebarCollapsed">Kuis / Ujian</span>
                </a>
            </li>
            <li>
                <a href="{{ route('teacher.tasks') }}" wire:navigate title="Tugas"
                    @click="$dispatch('close-mobile-sidebar')"
                    class="flex items-center p-3 text-gray-700 transition-all duration-200 border {{ request()->routeIs('teacher.tasks*') ? 'bg-gray-500 text-white font-bold shadow-md' : 'hover:bg-gray-300 hover:shadow-sm' }}"
                    :class="{ 'justify-center': sidebarCollapsed }">
                    <i class="w-6 text-center fa-solid fa-clipboard-list"></i>
                    <span class="ml-3" x-show="!sidebarCollapsed">Tugas</span>
                </a>
            </li>
            <li>
                <a href="{{ route('teacher.scores.tasks') }}" wire:navigate title="Beri Nilai Tugas"
                    @click="$dispatch('close-mobile-sidebar')"
                    class="flex items-center p-3 text-gray-700 transition-all duration-200 border {{ request()->routeIs('teacher.scores*') ? 'bg-gray-500 text-white font-bold shadow-md' : 'hover:bg-gray-300 hover:shadow-sm' }}"
                    :class="{ 'justify-center': sidebarCollapsed }">
                    <i class="w-6 text-center fa-solid fa-marker"></i>
                    <span class="ml-3" x-show="!sidebarCollapsed">Beri Nilai Tugas</span>
                </a>
            </li>
            <li>
                <a href="{{ route('teacher.games') }}" wire:navigate title="Game Edukatif"
                    @click="$dispatch('close-mobile-sidebar')"
                    class="flex items-center p-3 text-gray-700 transition-all duration-200 border {{ request()->routeIs('teacher.games') ? 'bg-gray-500 text-white font-bold shadow-md' : 'hover:bg-gray-300 hover:shadow-sm' }}"
                    :class="{ 'justify-center': sidebarCollapsed }">
                    <i class="w-6 text-center fa-solid fa-gamepad"></i>
                    <span class="ml-3" x-show="!sidebarCollapsed">Game Edukatif</span>
                </a>
            </li>
            <li>
                <a href="{{ route('teacher.rankings') }}" wire:navigate title="Ranking Siswa"
                    @click="$dispatch('close-mobile-sidebar')"
                    class="flex items-center p-3 text-gray-700 transition-all duration-200 border {{ request()->routeIs('teacher.rankings') ? 'bg-gray-500 text-white font-bold shadow-md' : 'hover:bg-gray-300 hover:shadow-sm' }}"
                    :class="{ 'justify-center': sidebarCollapsed }">
                    <i class="w-6 text-center fa-solid fa-trophy"></i>
                    <span class="ml-3" x-show="!sidebarCollapsed">Nilai Siswa</span>
                </a>
            </li>
            <li>
                <a href="{{ route('teacher.about-us') }}" wire:navigate title="Tentang Aplikasi"
                    @click="$dispatch('close-mobile-sidebar')"
                    class="flex items-center p-3 text-gray-700 transition-all duration-200 border {{ request()->routeIs('teacher.about-us') ? 'bg-gray-500 text-white font-bold shadow-md' : 'hover:bg-gray-300 hover:shadow-sm' }}"
                    :class="{ 'justify-center': sidebarCollapsed }">
                    <i class="w-6 text-center fa-solid fa-info-circle"></i>
                    <span class="ml-3" x-show="!sidebarCollapsed">About Us</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="p-4 mt-auto border-t border-gray-300" x-show="!sidebarCollapsed">
        <div class="text-xs text-center text-gray-500">
            <p>&copy; 2025 Website Pembelajaran Digital SD</p>
        </div>
    </div>
</aside>

<script>
    document.addEventListener('alpine:init', () => {
        // Listen untuk event close-mobile-sidebar
        document.addEventListener('close-mobile-sidebar', () => {
            // Cek jika kita berada di tampilan mobile
            if (window.innerWidth < 1024) {
                // Tunggu sebentar untuk navigasi selesai, lalu tutup sidebar
                setTimeout(() => {
                    // Pastikan Alpine.store('sidebar') ada sebelum digunakan
                    if (Alpine.store('sidebar')) {
                        Alpine.store('sidebar').mobileSidebarOpen = false;
                    }
                }, 150); // Sedikit delay untuk transisi navigasi
            }
        });
    });
</script>
