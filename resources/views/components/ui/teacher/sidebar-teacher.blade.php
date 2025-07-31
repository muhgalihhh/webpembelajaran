<aside
    class="fixed inset-y-0 left-0 z-40 flex flex-col flex-shrink-0 space-y-4 overflow-y-auto bg-gray-200 border-r border-gray-200 shadow-lg lg:relative lg:translate-x-0"
    :class="{
        'w-64': !sidebarCollapsed,
        'lg:w-20 w-64': sidebarCollapsed,
        'translate-x-0': mobileSidebarOpen,
        '-translate-x-full': !mobileSidebarOpen
    }">

    <div class="flex items-center p-2 bg-blue-950" :class="sidebarCollapsed ? 'justify-center' : 'justify-between'">
        <div x-show="!sidebarCollapsed" class="flex flex-col items-start space-x-2 text-white">
            <div class="flex items-center mb-2 space-x-2">
                <img src="{{ asset('images/logo sd.png') }}" alt="Logo" class="w-10 ">
                <span class="text-lg font-bold">Guru</span>
            </div>
            <span class="text-sm text-gray-500">{{ Auth::user()->name }}</span>
            <span class="text-xs text-gray-400">{{ Auth::user()->email }}</span>
        </div>

        {{-- Tombol ini hanya muncul di desktop --}}
        <button @click="sidebarCollapsed = !sidebarCollapsed"
            class="hidden p-2 bg-white rounded-full lg:block hover:bg-gray-200">
            <i class="fa-solid fa-chevron-left" :class="{ 'rotate-180': sidebarCollapsed }"></i>
        </button>
    </div>



    <nav class="flex-grow px-2">
        <p class="px-2 mt-4 mb-2 text-xs font-semibold text-gray-400 uppercase"
            :class="{ 'text-center': sidebarCollapsed }" x-show="!sidebarCollapsed">
            Menu Utama
        </p>
        <ul class="space-y-2">
            <li>
                <a href="{{ route('teacher.dashboard') }}" wire:navigate title="Dashboard"
                    class="flex bg-gray-500 items-center border border-black p-3 text-gray-700{{ request()->routeIs('teacher.dashboard') ? 'bg-gray-700 text-white font-bold' : 'hover:bg-gray-300' }}"
                    :class="{ 'justify-center': sidebarCollapsed }">
                    <i class="w-6 text-center fa-solid fa-chart-line"></i>
                    <span class="ml-3" x-show="!sidebarCollapsed">Aktivitas Siswa</span>
                </a>
            </li>
            <li>
                <a href="{{ route('teacher.materials') }}" wire:navigate title="Materi Pembelajaran"
                    class="flex bg-gray-500 items-center border border-black p-3 text-gray-700{{ request()->routeIs('teacher.materials*') ? 'bg-gray-700 text-white font-bold' : 'hover:bg-gray-300' }}"
                    :class="{ 'justify-center': sidebarCollapsed }">
                    <i class="w-6 text-center fa-solid fa-book-open"></i>
                    <span class="ml-3" x-show="!sidebarCollapsed">Materi Pembelajaran</span>
                </a>
            </li>
            <li>
                <a href="{{ route('teacher.quizzes') }}" wire:navigate title="Kuis / Ujian"
                    class="flex bg-gray-500 items-center border border-black p-3 text-gray-700{{ request()->routeIs('teacher.quizzes*') ? 'bg-gray-700 text-white font-bold' : 'hover:bg-gray-300' }}"
                    :class="{ 'justify-center': sidebarCollapsed }">
                    <i class="w-6 text-center fa-solid fa-file-signature"></i>
                    <span class="ml-3" x-show="!sidebarCollapsed">Kuis / Ujian</span>
                </a>
            </li>
            <li>
                <a href="{{ route('teacher.tasks') }}" wire:navigate title="Tugas"
                    class="flex bg-gray-500 items-center border border-black p-3 text-gray-700{{ request()->routeIs('teacher.tasks') ? 'bg-gray-700 text-white font-bold' : 'hover:bg-gray-300' }}"
                    :class="{ 'justify-center': sidebarCollapsed }">
                    <i class="w-6 text-center fa-solid fa-clipboard-list"></i>
                    <span class="ml-3" x-show="!sidebarCollapsed">Tugas</span>
                </a>
            </li>
            <li>
                <a href="{{ route('teacher.scores.tasks') }}" wire:navigate title="Tugas"
                    class="flex bg-gray-500 items-center border border-black p-3 text-gray-700{{ request()->routeIs('teacher.scores*') ? 'bg-gray-700 text-white font-bold' : 'hover:bg-gray-300' }}"
                    :class="{ 'justify-center': sidebarCollapsed }">
                    <i class="w-6 text-center fa-solid fa-clipboard-list"></i>
                    <span class="ml-3" x-show="!sidebarCollapsed">Beri Nilai Tugas</span>
                </a>
            </li>
            <li>
                <a href="{{ route('teacher.games') }}" wire:navigate title="Game Edukatif"
                    class="flex bg-gray-500 items-center border border-black p-3 text-gray-700{{ request()->routeIs('teacher.games') ? 'bg-gray-700 text-white font-bold' : 'hover:bg-gray-300' }}"
                    :class="{ 'justify-center': sidebarCollapsed }">
                    <i class="w-6 text-center fa-solid fa-gamepad"></i>
                    <span class="ml-3" x-show="!sidebarCollapsed">Game Edukatif</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
