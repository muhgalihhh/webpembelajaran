<div x-data="{ show: false }" x-on:open-logout-modal.window="show = true" x-show="show" x-cloak
    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[9999] flex items-center justify-center">

    {{-- Background Overlay Transparan - Menggunakan style inline sebagai fallback --}}
    <div class="absolute inset-0 bg-black bg-opacity-50" style="background-color: rgba(0, 0, 0, 0.5);"
        @click="show = false"></div>

    {{-- Konten Modal --}}
    <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="relative z-10 w-full max-w-sm p-6 mx-4 bg-white rounded-lg shadow-xl">

        {{-- Header --}}
        <div class="flex items-center justify-between pb-3 border-b">
            <h2 class="text-xl font-bold text-gray-800">Konfirmasi Logout</h2>
            <button @click="show = false" class="text-gray-500 hover:text-gray-800">
                <i class="fas fa-times fa-lg"></i>
            </button>
        </div>

        {{-- Body --}}
        <div class="py-5 text-gray-700">
            <p>Apakah Anda yakin ingin keluar dari aplikasi?</p>
        </div>

        {{-- Footer --}}
        <div class="flex justify-end mt-4 space-x-3">
            <button @click="show = false"
                class="px-4 py-2 font-semibold text-gray-800 transition bg-gray-200 rounded-md hover:bg-gray-300">
                Batal
            </button>


            <button @click="$dispatch('perform-logout')"
                class="px-4 py-2 font-semibold text-white transition bg-red-600 rounded-md hover:bg-red-700">
                Ya, Keluar
            </button>
        </div>
    </div>
</div>
