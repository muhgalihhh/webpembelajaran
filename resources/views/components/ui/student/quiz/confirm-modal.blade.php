@props([
    'show' => false,

    'icon' => 'fa-solid fa-circle-question',
    'iconColor' => 'text-white',
    'title' => 'Apakah Anda Yakin?',
    'message' => 'Tindakan ini akan diproses.',
    'confirmText' => 'Ya, Lanjutkan',
    'cancelText' => 'Batal',

    // Properti untuk aksi
    'wireConfirmAction' => '',
    'wireCancelAction' => '',
])

<div x-data="{ show: @entangle($attributes->wire('model')) }" x-show="show" x-on:keydown.escape.window="show = false" x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(0, 0, 0, 0.5);"
    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    {{-- Konten Modal --}}
    <div @if ($wireCancelAction) @click.away="$wire.{{ $wireCancelAction }}()"
        @else
            @click.away="show = false" @endif
        class="relative w-full max-w-sm p-6 text-center text-white border-4 rounded-lg shadow-xl"
        style="background-color: #38A2AC; border-color: #2c7a7b;" {{-- Warna dari gambar, dengan border lebih gelap --}} x-show="show"
        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
        {{-- Ikon --}}
        <div class="flex justify-center mb-4">
            <div class="flex items-center justify-center w-20 h-20 bg-white rounded-full bg-opacity-20">
                <i class="{{ $icon }} text-6xl {{ $iconColor }}"></i>
            </div>
        </div>


        <h2 class="mb-2 text-2xl font-bold">{{ $title }}</h2>
        <p class="mb-6 text-base">{{ $message }}</p>


        <div class="flex flex-col-reverse w-full gap-3 sm:flex-row sm:justify-center">
            {{-- Tombol Batal --}}
            <button
                @if ($wireCancelAction) wire:click="{{ $wireCancelAction }}()"
                @else
                    @click="show = false" @endif
                class="w-full px-8 py-3 font-bold text-black bg-white border-2 border-gray-400 rounded-lg sm:w-auto hover:bg-gray-100">
                {{ $cancelText }}
            </button>


            <button
                @if ($wireConfirmAction) wire:click="{{ $wireConfirmAction }}"
                @else
                    @click="show = false" @endif
                class="w-full px-8 py-3 font-bold text-white bg-green-500 border-2 border-green-700 rounded-lg sm:w-auto hover:bg-green-600">
                {{ $confirmText }}
            </button>
        </div>
    </div>
</div>
