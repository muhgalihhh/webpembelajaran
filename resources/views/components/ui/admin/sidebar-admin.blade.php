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

    <!-- Header Section dengan Close Button untuk Mobile -->
    <div class="flex items-center p-2 bg-blue-950" :class="sidebarCollapsed ? 'justify-center' : 'justify-between'">
        <div x-show="!sidebarCollapsed" class="flex flex-col items-start flex-1 space-x-2 text-white">
            <div class="flex items-center w-full mb-2 space-x-2">
                <img src="{{ asset('images/logo sd.png') }}" alt="Logo" class="w-10">
                <div class="flex-1">
                    <span class="text-lg font-bold">ADMIN</span>
                </div>
                <!-- Close Button untuk Mobile - tampil di samping logo -->
                <button @click="mobileSidebarOpen = false"
                    class="p-2 text-white transition-colors duration-200 rounded-full lg:hidden hover:bg-blue-800">
                    <i class="text-sm fas fa-times"></i>
                </button>
            </div>
            <span class="text-sm text-gray-300">{{ Auth::user()->name }}</span>
            <span class="text-xs text-gray-400">{{ Auth::user()->email }}</span>
        </div>

        <!-- Collapsed state dengan close button untuk mobile -->
        <div x-show="sidebarCollapsed" class="flex items-center justify-between w-full">
            <img src="{{ asset('images/logo sd.png') }}" alt="Logo" class="w-8 mx-auto">
            <!-- Close Button untuk Mobile saat collapsed -->
            <button @click="mobileSidebarOpen = false"
                class="absolute p-2 text-white transition-colors duration-200 rounded-full lg:hidden hover:bg-blue-800 top-2 right-2">
                <i class="text-sm fas fa-times"></i>
            </button>
        </div>

        <!-- Toggle Collapse Button (Desktop Only) -->
        <button @click="sidebarCollapsed = !sidebarCollapsed"
            class="hidden p-2 transition-colors duration-200 bg-white rounded-full lg:block hover:bg-gray-200"
            title="Toggle Sidebar">
            <i class="transition-transform duration-300 fa-solid fa-chevron-left"
                :class="{ 'rotate-180': sidebarCollapsed }"></i>
        </button>
    </div>

    <!-- Navigation Section -->
    <nav class="flex-grow px-2">
        <!-- Dashboard Link -->
        <a href="{{ route('admin.dashboard') }}" wire:navigate title="Dashboard"
            @click="$dispatch('close-mobile-sidebar')"
            class="flex items-center p-3 my-2 text-gray-700 transition-all duration-200 border {{ request()->routeIs('admin.dashboard') ? 'bg-gray-500 text-white font-bold shadow-md' : 'hover:bg-gray-300 hover:shadow-sm' }}"
            :class="{ 'justify-center': sidebarCollapsed }">
            <i class="w-6 text-center fa-solid fa-tachometer-alt"></i>
            <span class="ml-3" x-show="!sidebarCollapsed">Dashboard</span>
        </a>

        <!-- Section Title -->
        <div class="mt-6 mb-3" x-show="!sidebarCollapsed">
            <p class="px-3 text-xs font-semibold tracking-wider text-gray-500 uppercase">Manajemen</p>
        </div>

        <!-- Management Links -->
        <ul class="space-y-1">
            <li>
                <a href="{{ route('admin.manage-teachers') }}" wire:navigate title="Manajemen Guru"
                    @click="$dispatch('close-mobile-sidebar')"
                    class="flex items-center p-3 text-gray-700 transition-all duration-200 border {{ request()->routeIs('admin.manage-teachers') ? 'bg-gray-500 text-white font-bold shadow-md' : 'hover:bg-gray-300 hover:shadow-sm' }}"
                    :class="{ 'justify-center': sidebarCollapsed }">
                    <i class="w-6 text-center fa-solid fa-chalkboard-teacher"></i>
                    <span class="ml-3" x-show="!sidebarCollapsed">Manajemen Guru</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.manage-students') }}" wire:navigate title="Manajemen Siswa"
                    @click="$dispatch('close-mobile-sidebar')"
                    class="flex items-center p-3 text-gray-700 transition-all duration-200 border {{ request()->routeIs('admin.manage-students') ? 'bg-gray-500 text-white font-bold shadow-md' : 'hover:bg-gray-300 hover:shadow-sm' }}"
                    :class="{ 'justify-center': sidebarCollapsed }">
                    <i class="w-6 text-center fa-solid fa-user-graduate"></i>
                    <span class="ml-3" x-show="!sidebarCollapsed">Manajemen Siswa</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.manage-classes') }}" wire:navigate title="Manajemen Kelas"
                    @click="$dispatch('close-mobile-sidebar')"
                    class="flex items-center p-3 text-gray-700 transition-all duration-200 border {{ request()->routeIs('admin.manage-classes') ? 'bg-gray-500 text-white font-bold shadow-md' : 'hover:bg-gray-300 hover:shadow-sm' }}"
                    :class="{ 'justify-center': sidebarCollapsed }">
                    <i class="w-6 text-center fa-solid fa-school"></i>
                    <span class="ml-3" x-show="!sidebarCollapsed">Manajemen Kelas</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.manage-subjects') }}" wire:navigate title="Manajemen Mapel"
                    @click="$dispatch('close-mobile-sidebar')"
                    class="flex items-center p-3 text-gray-700 transition-all duration-200 border {{ request()->routeIs('admin.manage-subjects') ? 'bg-gray-500 text-white font-bold shadow-md' : 'hover:bg-gray-300 hover:shadow-sm' }}"
                    :class="{ 'justify-center': sidebarCollapsed }">
                    <i class="w-6 text-center fa-solid fa-book"></i>
                    <span class="ml-3" x-show="!sidebarCollapsed">Manajemen Mapel</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.manage-curriculums') }}" wire:navigate title="Manajemen Kurikulum"
                    @click="$dispatch('close-mobile-sidebar')"
                    class="flex items-center p-3 text-gray-700 transition-all duration-200 border {{ request()->routeIs('admin.manage-curriculums') ? 'bg-gray-500 text-white font-bold shadow-md' : 'hover:bg-gray-300 hover:shadow-sm' }}"
                    :class="{ 'justify-center': sidebarCollapsed }">
                    <i class="w-6 text-center fa-solid fa-scroll"></i>
                    <span class="ml-3" x-show="!sidebarCollapsed">Manajemen Kurikulum</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Footer Section (Optional) -->
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
            // Tunggu sebentar untuk navigasi selesai, lalu tutup sidebar
            setTimeout(() => {
                Alpine.store('sidebar', {
                    mobileSidebarOpen: false
                });
            }, 100);
        });
    });
</script>
