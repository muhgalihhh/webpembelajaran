<div x-data="{ show: false, title: '', message: '', confirmEvent: '', denyEvent: '' }"
    x-on:open-confirm-dialog.window="
        show = true;
        title = $event.detail.title;
        message = $event.detail.message;
        confirmEvent = $event.detail.confirmEvent;
        denyEvent = $event.detail.denyEvent || ''; // Opsional jika ada denyEvent
    "
    x-on:close-confirm-dialog.window="show = false" x-show="show" x-cloak x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[9999] flex items-center justify-center">

    {{-- Background Overlay --}}
    <div class="absolute inset-0 bg-black bg-opacity-50" style="background-color: rgba(0, 0, 0, 0.5);"
        @click="show = false"></div>

    {{-- Modal Content --}}
    <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="relative z-10 w-full max-w-sm p-6 mx-4 text-center text-white bg-teal-600 border-4 border-teal-800 rounded-lg shadow-xl">

        {{-- Icon Peringatan --}}
        <div class="flex justify-center mb-4">
            <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                </path>
            </svg>
        </div>

        {{-- Judul dan Pesan --}}
        <h2 class="mb-2 text-xl font-bold" x-text="title"></h2>
        <p class="mb-6 text-sm" x-text="message"></p>

        {{-- Tombol Aksi --}}
        <div class="flex justify-center space-x-4">
            <button @click="show = false; if(denyEvent) $dispatch(denyEvent)"
                class="px-6 py-2 font-semibold text-teal-800 transition-colors duration-200 bg-white border border-teal-800 rounded-lg hover:bg-gray-100">
                Belum Yakin
            </button>
            <button @click="show = false; $dispatch(confirmEvent)"
                class="px-6 py-2 font-semibold text-white transition-colors duration-200 bg-teal-800 rounded-lg hover:bg-teal-700">
                Yakin
            </button>
        </div>
    </div>
</div>
