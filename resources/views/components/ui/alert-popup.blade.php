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

<div x-data="{ show: false, message: '', type: 'success' }"
    x-on:{{ $on }}.window="
        console.log('Event received:', $event.detail);
        message = $event.detail.message || $event.detail[0]?.message || '';
        type = $event.detail.type || $event.detail[0]?.type || 'success';
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
    <div class="flex items-start">
        <div class="flex-1 py-1">
            <div class="flex items-center mb-2">
                <template x-if="type === 'success'">
                    <i class="mr-2 text-green-600 fa-solid fa-check-circle"></i>
                </template>
                <template x-if="type === 'error'">
                    <i class="mr-2 text-red-600 fa-solid fa-times-circle"></i>
                </template>
                <template x-if="type === 'warning'">
                    <i class="mr-2 text-yellow-600 fa-solid fa-exclamation-triangle"></i>
                </template>
                <p class="text-sm font-bold"
                    x-text="type === 'success' ? 'Berhasil' : (type === 'error' ? 'Error' : 'Peringatan')"></p>
            </div>
            <p class="text-sm" x-text="message"></p>
        </div>
        <button @click="show = false" class="ml-4 text-xl font-bold text-gray-500 hover:text-gray-700">&times;</button>
    </div>
</div>

@if (session()->has('flash-message'))
    @php
        $flashData = session('flash-message');
        $flashType = $flashData['type'] ?? 'success';
        $flashMessage = $flashData['message'] ?? '';
    @endphp

    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
        x-transition:enter="transform ease-out duration-300 transition"
        x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
        x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed z-50 p-4 border-l-4 rounded-md shadow-lg top-5 right-5 {{ $colors[$flashType] }}" x-cloak>
        <div class="flex items-start">
            <div class="flex-1 py-1">
                <div class="flex items-center mb-2">
                    @if ($flashType === 'success')
                        <i class="mr-2 text-green-600 fa-solid fa-check-circle"></i>
                        <p class="text-sm font-bold">Berhasil</p>
                    @elseif($flashType === 'error')
                        <i class="mr-2 text-red-600 fa-solid fa-times-circle"></i>
                        <p class="text-sm font-bold">Error</p>
                    @else
                        <i class="mr-2 text-yellow-600 fa-solid fa-exclamation-triangle"></i>
                        <p class="text-sm font-bold">Peringatan</p>
                    @endif
                </div>
                <p class="text-sm">{{ $flashMessage }}</p>
            </div>
            <button @click="show = false"
                class="ml-4 text-xl font-bold text-gray-500 hover:text-gray-700">&times;</button>
        </div>
    </div>
@endif
