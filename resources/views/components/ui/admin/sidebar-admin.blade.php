{{-- Perubahan utama: `fixed` hanya untuk mobile (di bawah lg), di desktop (lg) menjadi `relative` --}}
<aside
    class="fixed inset-y-0 left-0 z-40 flex flex-col flex-shrink-0 space-y-4 overflow-y-auto bg-white border-r border-gray-200 shadow-lg lg:relative lg:translate-x-0"
    :class="{
        'w-64': !sidebarCollapsed,
        'lg:w-20 w-64': sidebarCollapsed,
        /* w-64 untuk mobile saat sidebarCollapsed true */
        'translate-x-0': mobileSidebarOpen,
        '-translate-x-full': !mobileSidebarOpen
    }">

    {{-- Logo dan Tombol Collapse --}}
    <div class="flex items-center p-2 bg-blue-950" :class="sidebarCollapsed ? 'justify-center' : 'justify-between'">
        <div x-show="!sidebarCollapsed" class="flex flex-col items-start space-x-2 text-white">
            <div class="flex items-center mb-2 space-x-2">
                <img src="{{ asset('images/logo sd.png') }}" alt="Logo" class="w-10 ">
                <span class="text-lg font-bold">ADMIN</span>
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

    {{-- Menu Navigasi --}}
    <nav class="flex-grow">
        <a href="{{ route('admin.dashboard') }}" wire:navigate title="Dashboard"
            class="flex items-center border border-black p-3 my-2 text-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-200 font-bold' : 'hover:bg-gray-100' }}"
            :class="{ 'justify-center': sidebarCollapsed }">
            <i class="w-6 text-center fa-solid fa-tachometer-alt"></i>
            <span class="ml-3" x-show="!sidebarCollapsed">Dashboard</span>
        </a>

        <p class="mt-4 mb-2 text-xs font-semibold text-gray-400 uppercase" :class="{ 'text-center': sidebarCollapsed }"
            x-show="!sidebarCollapsed">MANAJEMEN</p>

        <ul class="space-y-2">
            <li>
                <a href="{{ route('admin.manage-teachers') }}" wire:navigate title="Manajemen Guru"
                    class="flex items-center border border-black p-3 text-gray-700 {{ request()->routeIs('admin.manage-teachers') ? 'bg-gray-200 font-bold' : 'hover:bg-gray-100' }}"
                    :class="{ 'justify-center': sidebarCollapsed }">
                    <i class="w-6 text-center fa-solid fa-chalkboard-teacher"></i>
                    <span class="ml-3" x-show="!sidebarCollapsed">Manajemen Guru</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.manage-students') }}" wire:navigate title="Manajemen Siswa"
                    class="flex items-center border border-black p-3 text-gray-700 {{ request()->routeIs('admin.manage-students') ? 'bg-gray-200 font-bold' : 'hover:bg-gray-100' }}"
                    :class="{ 'justify-center': sidebarCollapsed }">
                    <i class="w-6 text-center fa-solid fa-user-graduate"></i>
                    <span class="ml-3" x-show="!sidebarCollapsed">Manajemen Siswa</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.manage-classes') }}" wire:navigate title="Manajemen Kelas"
                    class="flex items-center border border-black p-3 text-gray-700 {{ request()->routeIs('admin.manage-classes') ? 'bg-gray-200 font-bold' : 'hover:bg-gray-100' }}"
                    :class="{ 'justify-center': sidebarCollapsed }">
                    <i class="w-6 text-center fa-solid fa-school"></i>
                    <span class="ml-3" x-show="!sidebarCollapsed">Manajemen Kelas</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.manage-subjects') }}" wire:navigate title="Manajemen Mapel"
                    class="flex items-center border border-black p-3 text-gray-700 {{ request()->routeIs('admin.manage-subjects') ? 'bg-gray-200 font-bold' : 'hover:bg-gray-100' }}"
                    :class="{ 'justify-center': sidebarCollapsed }">
                    <i class="w-6 text-center fa-solid fa-book"></i>
                    <span class="ml-3" x-show="!sidebarCollapsed">Manajemen Mapel</span>
                </a>
            </li>
        </ul>
    </nav>

    {{-- Tombol Logout --}}
    <div>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            title="Logout" class="flex items-center p-3 text-red-500 hover:bg-red-100"
            :class="{ 'justify-center': sidebarCollapsed }">
            <i class="w-6 text-center fa-solid fa-arrow-right-from-bracket"></i>
            <span class="ml-3" x-show="!sidebarCollapsed">Logout</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
    </div>
</aside>
