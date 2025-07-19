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
        class="bg-white rounded-lg shadow-xl p-6 w-full max-w-sm mx-4 relative z-10">

        {{-- Header --}}
        <div class="flex justify-between items-center pb-3 border-b">
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
        <div class="mt-4 flex justify-end space-x-3">
            <button @click="show = false"
                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md font-semibold hover:bg-gray-300 transition">
                Batal
            </button>


            <button @click="$dispatch('perform-logout')"
                class="px-4 py-2 bg-red-600 text-white rounded-md font-semibold hover:bg-red-700 transition">
                Ya, Keluar
            </button>
        </div>
    </div>
</div>
