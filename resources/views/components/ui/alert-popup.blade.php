@props([
    'type' => 'success',
    'on' => 'flash-message',
])

@php
    $colors = [
        'success' => 'bg-green-100 border-green-400 text-green-700',
        'error' => 'bg-red-100 border-red-400 text-red-700',
        'warning' => 'bg-yellow-100 border-yellow-400 text-yellow-700',
    ];
@endphp

{{--
    Komponen ini dikontrol oleh Alpine.js.
    - x-on:{{ $on }}.window: Mendengarkan event dari Livewire.
    - setTimeout: Otomatis menyembunyikan alert setelah 5 detik.
--}}
<div x-data="{ show: false, message: '', type: 'success' }"
    x-on:{{ $on }}.window="
        message = $event.detail.message;
        type = $event.detail.type || 'success';
        show = true;
        setTimeout(() => show = false, 5000)
     "
    x-show="show" x-transition:enter="transform ease-out duration-300 transition"
    x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" class="fixed z-50 p-4 border-l-4 rounded-md shadow-lg top-5 right-5"
    :class="{
        '{{ $colors['success'] }}': type === 'success',
        '{{ $colors['error'] }}': type === 'error',
        '{{ $colors['warning'] }}': type === 'warning'
    }"
    x-cloak>
    <div class="flex">
        <div class="py-1">
            <p class="font-bold" x-text="type === 'success' ? 'Success' : (type === 'error' ? 'Error' : 'Warning')"></p>
            <p class="text-sm" x-text="message"></p>
        </div>
        <button @click="show = false" class="ml-4 text-xl">&times;</button>
    </div>
</div>
