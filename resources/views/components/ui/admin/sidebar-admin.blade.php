{{-- <div x-show="mobileSidebarOpen" class="fixed inset-0 z-30 bg-opacity-50 lg:hidden" @click="mobileSidebarOpen = false">
</div> --}}

<aside
    class="fixed inset-y-0 left-0 z-40 flex flex-col p-4 space-y-4 bg-white border-r border-gray-200 shadow-lg lg:relative lg:translate-x-0"
    :class="{
        'w-64': !sidebarCollapsed,
        'w-20': sidebarCollapsed,
        'translate-x-0': mobileSidebarOpen,
        '-translate-x-full': !mobileSidebarOpen
    }">

    <div class="flex items-center" :class="sidebarCollapsed ? 'justify-center' : 'justify-between'">
        <div x-show="!sidebarCollapsed" class="flex items-center space-x-2">
            <img src="{{ asset('images/logo sd.png') }}" alt="Logo" class="w-10 h-10">
            <span class="text-lg font-bold">ADMIN</span>
        </div>
        <button @click="sidebarCollapsed = !sidebarCollapsed" class="hidden p-2 rounded-full lg:block hover:bg-gray-200">
            <i class="fa-solid fa-chevron-left" :class="{ 'rotate-180': sidebarCollapsed }"></i>
        </button>
    </div>

    <nav class="flex-grow">
        <a href="{{ route('admin.dashboard') }}" wire:navigate title="Dashboard"
            class="flex items-center p-3 my-2 text-gray-700 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-gray-200 font-bold' : 'hover:bg-gray-100' }}"
            :class="{ 'justify-center': sidebarCollapsed }">
            <i class="w-6 text-center fa-solid fa-tachometer-alt"></i>
            <span class="ml-3" x-show="!sidebarCollapsed">Dashboard</span>
        </a>

        <p class="mt-4 mb-2 text-xs font-semibold text-gray-400 uppercase" :class="{ 'text-center': sidebarCollapsed }"
            x-show="!sidebarCollapsed">MANAJEMEN</p>

        <ul class="space-y-2">
            <li>
                <a href="{{ route('admin.manage-teachers') }}" wire:navigate title="Manajemen Guru"
                    class="flex items-center p-3 text-gray-700 rounded-lg {{ request()->routeIs('admin.manage-teachers') ? 'bg-gray-200 font-bold' : 'hover:bg-gray-100' }}"
                    :class="{ 'justify-center': sidebarCollapsed }">
                    <i class="w-6 text-center fa-solid fa-chalkboard-teacher"></i>
                    <span class="ml-3" x-show="!sidebarCollapsed">Manajemen Guru</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.manage-students') }}" wire:navigate title="Manajemen Siswa"
                    class="flex items-center p-3 text-gray-700 rounded-lg {{ request()->routeIs('admin.manage-students') ? 'bg-gray-200 font-bold' : 'hover:bg-gray-100' }}"
                    :class="{ 'justify-center': sidebarCollapsed }">
                    <i class="w-6 text-center fa-solid fa-user-graduate"></i>
                    <span class="ml-3" x-show="!sidebarCollapsed">Manajemen Siswa</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.manage-classes') }}" wire:navigate title="Manajemen Kelas"
                    class="flex items-center p-3 text-gray-700 rounded-lg {{ request()->routeIs('admin.manage-classes') ? 'bg-gray-200 font-bold' : 'hover:bg-gray-100' }}"
                    :class="{ 'justify-center': sidebarCollapsed }">
                    <i class="w-6 text-center fa-solid fa-school"></i>
                    <span class="ml-3" x-show="!sidebarCollapsed">Manajemen Kelas</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.manage-subjects') }}" wire:navigate title="Manajemen Mapel"
                    class="flex items-center p-3 text-gray-700 rounded-lg {{ request()->routeIs('admin.manage-subjects') ? 'bg-gray-200 font-bold' : 'hover:bg-gray-100' }}"
                    :class="{ 'justify-center': sidebarCollapsed }">
                    <i class="w-6 text-center fa-solid fa-book"></i>
                    <span class="ml-3" x-show="!sidebarCollapsed">Manajemen Mapel</span>
                </a>
            </li>
        </ul>
    </nav>

    <div>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            title="Logout" class="flex items-center p-3 text-red-500 rounded-lg hover:bg-red-100"
            :class="{ 'justify-center': sidebarCollapsed }">
            <i class="w-6 text-center fa-solid fa-arrow-right-from-bracket"></i>
            <span class="ml-3" x-show="!sidebarCollapsed">Logout</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
    </div>
</aside>
