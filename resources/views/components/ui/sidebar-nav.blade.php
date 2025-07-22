    <div class="z-20 flex flex-col w-64 text-white bg-gray-300 shadow-lg">

        <nav class="flex-1 py-6 space-y-2">
            {{-- Name dan Greeting --}}
            <div class="p-2 mb-4 text-sm font-semibold text-left bg-blue-900 ">
                @auth
                    <h3 class="text-lg font-semibold">
                        Welcome, {{ Auth::user()->name }}!
                    </h3>
                    @if (Auth::user()->hasRole('admin'))
                        <div class="text-sm text-orange-300">({{ Auth::user()->getRoleNames()->first() }})</div>
                        <div class="text-sm text-gray-300">({{ Auth::user()->email }})</div>
                    @elseif (Auth::user()->hasRole('guru'))
                        <div class="text-sm text-orange-300">({{ Auth::user()->getRoleNames()->first() }})</div>
                        <span class="text-sm text-gray-300">({{ Auth::user()->email }})</span>
                    @endif
                @else
                    <span>Welcome to the Dashboard!</span>
                @endauth
            </div>
            {{-- Admin Navigation Links --}}
            @if (Auth::user()->hasRole('admin'))
                <a href="{{ route('admin.dashboard') }}" wire:navigate
                    class="sidebar-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt sidebar-nav-icon"></i> Dashboard
                </a>
                <a href="{{ route('admin.manage-teachers') }}" wire:navigate
                    class="sidebar-nav-item {{ request()->routeIs('admin.manage-teachers') ? 'active' : '' }}">
                    <i class="fas fa-chalkboard-teacher sidebar-nav-icon"></i> Manage Teachers
                </a>
                <a href="{{ route('admin.manage-students') }}" wire:navigate
                    class="sidebar-nav-item {{ request()->routeIs('admin.manage-students') ? 'active' : '' }}">
                    <i class="fas fa-user-graduate sidebar-nav-icon"></i> Manage Students
                </a>
                <a href="{{ route('admin.manage-classes') }}" wire:navigate
                    class="sidebar-nav-item {{ request()->routeIs('admin.manage-classes') ? 'active' : '' }}">
                    <i class="fas fa-school sidebar-nav-icon"></i> Manage Classes
                </a>
                <a href="{{ route('admin.manage-subjects') }}" wire:navigate
                    class="sidebar-nav-item {{ request()->routeIs('admin.manage-subjects') ? 'active' : '' }}">
                    <i class="fas fa-book sidebar-nav-icon"></i> Manage Subjects
                </a>
            @endif

            {{-- Teacher Navigation Links --}}
            @if (Auth::user()->hasRole('guru'))
                <a href="" wire:navigate
                    class="sidebar-nav-item {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt sidebar-nav-icon"></i> Dashboard
                </a>
                <a href="]" wire:navigate
                    class="sidebar-nav-item {{ request()->routeIs('teacher.create-quiz') ? 'active' : '' }}">
                    <i class="fas fa-question-circle sidebar-nav-icon"></i> Buat Soal/Kuis
                </a>
                <a href="]" wire:navigate
                    class="sidebar-nav-item {{ request()->routeIs('teacher.upload-task') ? 'active' : '' }}">
                    <i class="fas fa-upload sidebar-nav-icon"></i> Upload Tugas Siswa
                </a>
                <a href="" wire:navigate
                    class="sidebar-nav-item {{ request()->routeIs('teacher.upload-material') ? 'active' : '' }}">
                    <i class="fas fa-file-upload sidebar-nav-icon"></i> Upload Materi Mapel
                </a>
                <a href="" wire:navigate
                    class="sidebar-nav-item {{ request()->routeIs('teacher.check-rank') ? 'active' : '' }}">
                    <i class="fas fa-medal sidebar-nav-icon"></i> Cek Peringkat Siswa
                </a>
            @endif

        </nav>
    </div>
