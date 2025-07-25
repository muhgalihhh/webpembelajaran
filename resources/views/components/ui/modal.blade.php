@props(['id' => 'form-modal', 'maxWidth' => 'lg'])

@php
    // Menentukan kelas lebar maksimum berdasarkan prop
    $maxWidth = [
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-lg',
        'xl' => 'sm:max-w-xl',
        '2xl' => 'sm:max-w-2xl',
    ][$maxWidth];
@endphp


<div x-data="{ show: false }" x-on:open-modal.window="show = ($event.detail.id === '{{ $id }}')"
    x-on:close-modal.window="show = false" x-on:keydown.escape.window="show = false" x-show="show"
    class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6 sm:px-0" x-cloak>
    {{-- Latar Belakang Gelap (Overlay) --}}
    <div x-show="show" class="fixed inset-0 transition-opacity" @click="show = false"
        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="absolute inset-0 bg-black" style="background-color: rgba(0, 0, 0, 0.5);">

        </div>
    </div>

    {{-- Konten Modal --}}
    <div x-show="show"
        class="w-full bg-white rounded-lg shadow-xl transform transition-all sm:mx-auto {{ $maxWidth }} absolute z-10"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
        <div class="p-6">
            {{ $slot }}
        </div>
    </div>
</div>
