@props([
    'title' => 'Konfirmasi Tindakan',
    'message' => 'Apakah Anda yakin ingin melanjutkan?',
    'wireConfirmAction' => 'delete',
])

<div x-data="{ show: false }" x-on:open-confirm-modal.window="show = true" x-on:close-confirm-modal.window="show = false"
    x-on:keydown.escape.window="show = false" x-show="show" x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display: none;">
    <div @click.away="show = false" class="w-full max-w-sm p-6 mx-4 bg-white rounded-lg shadow-xl" x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">
        <h3 class="text-lg font-bold">{{ $title }}</h3>
        <p class="mt-2 text-sm text-gray-600">{{ $message }}</p>

        <div class="flex justify-end mt-6 space-x-4">
            {{-- Tombol Batal akan menutup modal via AlpineJS --}}
            <button @click="show = false" type="button"
                class="px-4 py-2 font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                Batal
            </button>
            {{-- Tombol Konfirmasi memanggil action Livewire dan menutup modal --}}
            <button wire:click="{{ $wireConfirmAction }}" type="button"
                class="px-4 py-2 font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                Ya, Lanjutkan
            </button>
        </div>
    </div>
</div>
