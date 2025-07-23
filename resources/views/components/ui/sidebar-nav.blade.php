 {{-- Sidebar Component --}}
 <div class="flex flex-col text-white bg-gray-400 shadow-lg sidebar"
     :class="{ 'expanded': sidebarOpen, 'collapsed': !sidebarOpen }">

     {{-- Toggle Button for Sidebar --}}
     <button @click="sidebarOpen = !sidebarOpen"
         class="p-2 text-white bg-blue-700 rounded-full shadow-lg toggle-btn focus:outline-none"
         :class="{ 'expanded': sidebarOpen, 'collapsed': !sidebarOpen }">
         <i class="fas" :class="sidebarOpen ? 'fa-chevron-left' : 'fa-chevron-right'"></i>
     </button>

     {{-- User Info Section --}}
     <div class="user-info">
         @auth
             <div class="flex items-center justify-center" :class="{ 'mb-2': sidebarOpen }">
                 <i class="text-2xl fas fa-user-circle" :class="{ 'mr-3': sidebarOpen }"></i>
                 <div class="user-details">
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
                 </div>
             </div>
         @else
             <div class="user-details">
                 <span>Welcome to the Dashboard!</span>
             </div>
         @endauth
     </div>

     {{-- Sidebar Content --}}
     <nav class="flex-1 px-2 space-y-2 overflow-hidden">
         {{-- Admin Navigation Links --}}
         @if (Auth::user()->hasRole('admin'))
             <a href="{{ route('admin.dashboard') }}" wire:navigate
                 class="sidebar-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                 <i class="fas fa-tachometer-alt sidebar-nav-icon"></i>
                 <span class="nav-text">Dashboard</span>
             </a>
             <a href="{{ route('admin.manage-teachers') }}" wire:navigate
                 class="sidebar-nav-item {{ request()->routeIs('admin.manage-teachers') ? 'active' : '' }}">
                 <i class="fas fa-chalkboard-teacher sidebar-nav-icon"></i>
                 <span class="nav-text">Manage Teachers</span>
             </a>
             <a href="{{ route('admin.manage-students') }}" wire:navigate
                 class="sidebar-nav-item {{ request()->routeIs('admin.manage-students') ? 'active' : '' }}">
                 <i class="fas fa-user-graduate sidebar-nav-icon"></i>
                 <span class="nav-text">Manage Students</span>
             </a>
             <a href="{{ route('admin.manage-classes') }}" wire:navigate
                 class="sidebar-nav-item {{ request()->routeIs('admin.manage-classes') ? 'active' : '' }}">
                 <i class="fas fa-school sidebar-nav-icon"></i>
                 <span class="nav-text">Manage Classes</span>
             </a>
             <a href="{{ route('admin.manage-subjects') }}" wire:navigate
                 class="sidebar-nav-item {{ request()->routeIs('admin.manage-subjects') ? 'active' : '' }}">
                 <i class="fas fa-book sidebar-nav-icon"></i>
                 <span class="nav-text">Manage Subjects</span>
             </a>
         @endif

         {{-- Teacher Navigation Links --}}
         @if (Auth::user()->hasRole('guru'))
             <a href="{{ route('teacher.dashboard') }}" wire:navigate
                 class="sidebar-nav-item {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
                 <i class="fas fa-tachometer-alt sidebar-nav-icon"></i>
                 <span class="nav-text">Dashboard</span>
             </a>
             <a href="{{ route('teacher.create-quiz') }}" wire:navigate
                 class="sidebar-nav-item {{ request()->routeIs('teacher.create-quiz') ? 'active' : '' }}">
                 <i class="fas fa-question-circle sidebar-nav-icon"></i>
                 <span class="nav-text">Buat Soal/Kuis</span>
             </a>
             <a href="{{ route('teacher.upload-task') }}" wire:navigate
                 class="sidebar-nav-item {{ request()->routeIs('teacher.upload-task') ? 'active' : '' }}">
                 <i class="fas fa-upload sidebar-nav-icon"></i>
                 <span class="nav-text">Upload Tugas Siswa</span>
             </a>
             <a href="{{ route('teacher.upload-material') }}" wire:navigate
                 class="sidebar-nav-item {{ request()->routeIs('teacher.upload-material') ? 'active' : '' }}">
                 <i class="fas fa-file-upload sidebar-nav-icon"></i>
                 <span class="nav-text">Upload Materi Mapel</span>
             </a>
             <a href="{{ route('teacher.check-rank') }}" wire:navigate
                 class="sidebar-nav-item {{ request()->routeIs('teacher.check-rank') ? 'active' : '' }}">
                 <i class="fas fa-medal sidebar-nav-icon"></i>
                 <span class="nav-text">Cek Peringkat Siswa</span>
             </a>
         @endif
     </nav>
 </div>
