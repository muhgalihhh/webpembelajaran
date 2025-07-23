<header x-data="{ dropdownOpen: false }" class="bg-[#4A90E2] text-white shadow-md p-4 flex justify-between items-center relative">
    <div class="flex items-center">
        <button @click.stop="mobileSidebarOpen = !mobileSidebarOpen" class="mr-3 text-white lg:hidden">
            <i class="text-xl fa-solid fa-bars"></i>
        </button>

        <img src="{{ asset('images/logo sd.png') }}" alt="Logo" class="hidden h-10 sm:block">
        <div class="ml-3">
            <h1 class="text-xl font-bold">Panel Admin</h1>
        </div>
    </div>

    <div class="relative">
        <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-2 focus:outline-none">
            <span>{{ Auth::user()->name }}</span>
            <div class="flex items-center justify-center w-8 h-8 bg-gray-300 rounded-full">
                <i class="text-gray-600 fa-solid fa-user-shield"></i>
            </div>
        </button>

        <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-cloak
            class="absolute right-0 z-10 w-48 mt-2 overflow-hidden bg-white rounded-md shadow-lg">
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-nav').submit();"
                class="block w-full px-4 py-2 text-sm text-left text-red-500 hover:bg-red-100">
                Logout
            </a>
            <form id="logout-form-nav" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
        </div>
    </div>
</header>
