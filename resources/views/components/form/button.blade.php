@props([
    'type' => 'button',
    'variant' => 'primary',
    'icon' => '',
    'wireClick' => '',
    'name' => '',
])

@php
    $baseClasses =
        'inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2';

    $variantClasses = [
        'primary' => 'text-white bg-blue-600 hover:bg-blue-700 focus:ring-blue-500',
        'secondary' => 'text-gray-700 bg-gray-200 hover:bg-gray-300 focus:ring-gray-500',
        'danger' => 'text-white bg-red-600 hover:bg-red-700 focus:ring-red-500',
    ];
@endphp

<button type="{{ $type }}" @if ($wireClick) wire:click="{{ $wireClick }}" @endif
    {{-- Atribut `class` digabungkan, memungkinkan kustomisasi dari luar --}}
    {{ $attributes->merge(['class' => $baseClasses . ' ' . ($variantClasses[$variant] ?? $variantClasses['primary'])]) }}>
    @if ($icon)
        <i class="{{ $icon }} @if ($slot->isNotEmpty()) -ml-1 mr-2 @endif"></i>
    @endif
    {{ $slot }}
</button>
