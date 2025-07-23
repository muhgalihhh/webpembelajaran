<header x-data="{ dropdownOpen: false }" class="bg-[#4A90E2] text-white shadow-md p-4 flex justify-between items-center relative">
    {{-- Sisi Kiri Navbar --}}
    <div class="flex items-center">
        <x-ui.logo-nav />
    </div>

    <div class="flex items-center justify-center gap-3">
        {{-- tombol ke halaman awal --}}
        <a href="{{ route('admin.index') }}" class="px-3 py-2 text-black bg-white border rounded-md hover:text-gray-200">
            <i class="fa-solid fa-house fa-lg"></i>
            <span class="hidden sm:inline">Halaman Awal</span>
        </a>
        <x-ui.profile-dropdown />
    </div>
</header>
