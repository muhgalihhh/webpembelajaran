<div x-data="{ show: false }" x-on:open-logout-modal.window="show = true" x-show="show" x-cloak
    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[9999] flex items-center justify-center p-4">


    <div class="absolute inset-0" @click="show = false" style="background-color: rgba(0, 0, 0, 0.5);"></div>


    <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="relative z-10 w-full max-w-md p-6 text-center bg-white border rounded-lg shadow-xl md:p-8">

        <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full">
            <i class="text-4xl text-red-600 fa-solid fa-right-from-bracket"></i>
        </div>

        {{-- Judul dan Pesan --}}
        <h2 class="text-2xl font-bold text-gray-800">Konfirmasi Logout</h2>
        <p class="mt-2 text-gray-600">Apakah Anda yakin ingin keluar dari sesi ini?</p>

        {{-- Tombol Aksi --}}
        <div class="flex justify-center mt-8 space-x-4">
            <button @click="show = false"
                class="px-6 py-3 font-semibold text-gray-700 transition bg-gray-100 border rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                Batal
            </button>

            <form method="POST" action="{{ route('logout') }}" class="inline-block">
                @csrf
                <button type="submit"
                    class="px-6 py-3 font-semibold text-white transition bg-red-600 border border-black rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Ya, Keluar
                </button>
            </form>
        </div>
    </div>
</div>
