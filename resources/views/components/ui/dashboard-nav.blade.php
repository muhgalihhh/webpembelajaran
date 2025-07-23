<header
    class="bg-[#4A90E2] text-white py-3 px-4 sm:px-6 flex justify-between items-center shadow-md w-full fixed top-0 left-0 z-50">

    {{-- Left Section: Title + Mobile Menu Button --}}
    <div class="flex items-center space-x-3">
        {{-- Mobile Hamburger Button (visible only on mobile) --}}
        @auth
            <button @click="$dispatch('toggle-sidebar')"
                class="p-2 text-white transition-colors duration-300 rounded-md md:hidden hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button>
        @endauth

        {{-- App Title --}}
        <div class="text-lg font-bold md:text-xl">Media Pembelajaran Digital</div>
    </div>

    {{-- Right Section: Navigation & Profile --}}
    <div class="flex items-center space-x-3">
        @auth
            {{-- Desktop Navigation Links (hidden on mobile) --}}
            <nav class="items-center hidden space-x-4 md:flex">
                @role('admin')
                    <a href="{{ route('admin.index') }}"
                        class="flex items-center justify-center gap-2 px-3 py-2 font-semibold text-black transition-colors duration-200 bg-white border border-black rounded-md hover:text-gray-600 hover:bg-gray-100"
                        wire:navigate>
                        <i class="fas fa-home"></i>
                        <span class="hidden lg:inline">Home</span>
                    </a>
                @endrole
                {{-- Add more desktop navigation links here if needed --}}
            </nav>

            {{-- Profile Dropdown (Desktop & Mobile) --}}
            <div class="relative z-40">
                <x-ui.profile-dropdown />
            </div>
        @else
            {{-- Buttons for Guest Users --}}
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.login') }}" wire:navigate
                    class="bg-white text-[#4A90E2] px-3 py-2 md:px-4 rounded-lg font-semibold flex items-center space-x-1 hover:bg-blue-500 hover:text-white transition-colors duration-200 text-sm md:text-base">
                    <i class="text-sm fas fa-user-shield md:text-lg"></i>
                    <span class="hidden sm:inline">ADMIN</span>
                </a>
                <a href="{{ route('register') }}"
                    class="bg-white text-[#4A90E2] px-3 py-2 md:px-4 rounded-lg font-semibold border border-[#4A90E2] hover:bg-[#4A90E2] hover:text-white transition-colors duration-200 text-sm md:text-base"
                    wire:navigate>
                    <span class="hidden sm:inline">Daftar</span>
                    <span class="sm:hidden">+</span>
                </a>
            </div>
        @endauth
    </div>
</header>
