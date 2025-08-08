@props([
    'type' => 'success',
    'on' => 'flash-message',
])

@php
    $colors = [
        'success' => [
            'bg' => 'bg-gradient-to-r from-green-50 to-emerald-50',
            'border' => 'border-l-4 border-green-500',
            'text' => 'text-green-800',
            'icon_bg' => 'bg-green-100',
            'icon_color' => 'text-green-600',
            'progress' => 'bg-green-500',
        ],
        'error' => [
            'bg' => 'bg-gradient-to-r from-red-50 to-rose-50',
            'border' => 'border-l-4 border-red-500',
            'text' => 'text-red-800',
            'icon_bg' => 'bg-red-100',
            'icon_color' => 'text-red-600',
            'progress' => 'bg-red-500',
        ],
        'warning' => [
            'bg' => 'bg-gradient-to-r from-yellow-50 to-amber-50',
            'border' => 'border-l-4 border-yellow-500',
            'text' => 'text-yellow-800',
            'icon_bg' => 'bg-yellow-100',
            'icon_color' => 'text-yellow-600',
            'progress' => 'bg-yellow-500',
        ],
        'info' => [
            'bg' => 'bg-gradient-to-r from-blue-50 to-sky-50',
            'border' => 'border-l-4 border-blue-500',
            'text' => 'text-blue-800',
            'icon_bg' => 'bg-blue-100',
            'icon_color' => 'text-blue-600',
            'progress' => 'bg-blue-500',
        ],
    ];
@endphp

<div x-data="{
    show: false,
    message: '',
    type: 'success',
    progress: 100,
    duration: 5000,
    interval: null,

    startProgressBar() {
        this.progress = 100;
        const stepTime = 50;
        const steps = this.duration / stepTime;
        const decrement = 100 / steps;

        this.interval = setInterval(() => {
            this.progress -= decrement;
            if (this.progress <= 0) {
                this.hideMessage();
            }
        }, stepTime);
    },

    hideMessage() {
        this.show = false;
        if (this.interval) {
            clearInterval(this.interval);
            this.interval = null;
        }
    },

    pauseProgress() {
        if (this.interval) {
            clearInterval(this.interval);
            this.interval = null;
        }
    },

    resumeProgress() {
        if (!this.interval && this.progress > 0) {
            this.startProgressBar();
        }
    }
}"
    x-on:{{ $on }}.window="
        message = $event.detail.message || $event.detail[0]?.message || '';
        type = $event.detail.type || $event.detail[0]?.type || 'success';
        show = true;
        startProgressBar();
    "
    x-show="show" x-transition:enter="transform ease-out duration-500 transition"
    x-transition:enter-start="translate-x-full opacity-0 scale-95"
    x-transition:enter-end="translate-x-0 opacity-100 scale-100" x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95 translate-x-full"
    @mouseenter="pauseProgress()" @mouseleave="resumeProgress()"
    class="fixed z-[9999] top-6 right-6 w-96 max-w-sm mx-auto overflow-hidden bg-white rounded-xl shadow-2xl backdrop-blur-sm"
    :class="{
        '{{ $colors['success']['bg'] }} {{ $colors['success']['border'] }}': type === 'success',
        '{{ $colors['error']['bg'] }} {{ $colors['error']['border'] }}': type === 'error',
        '{{ $colors['warning']['bg'] }} {{ $colors['warning']['border'] }}': type === 'warning',
        '{{ $colors['info']['bg'] }} {{ $colors['info']['border'] }}': type === 'info'
    }"
    x-cloak>

    <!-- Main Content -->
    <div class="p-4">
        <div class="flex items-start space-x-3">
            <!-- Icon Container -->
            <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-full"
                :class="{
                    '{{ $colors['success']['icon_bg'] }}': type === 'success',
                    '{{ $colors['error']['icon_bg'] }}': type === 'error',
                    '{{ $colors['warning']['icon_bg'] }}': type === 'warning',
                    '{{ $colors['info']['icon_bg'] }}': type === 'info'
                }">

                <!-- Success Icon -->
                <template x-if="type === 'success'">
                    <svg class="w-5 h-5 {{ $colors['success']['icon_color'] }}" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                </template>

                <!-- Error Icon -->
                <template x-if="type === 'error'">
                    <svg class="w-5 h-5 {{ $colors['error']['icon_color'] }}" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                </template>

                <!-- Warning Icon -->
                <template x-if="type === 'warning'">
                    <svg class="w-5 h-5 {{ $colors['warning']['icon_color'] }}" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                </template>

                <!-- Info Icon -->
                <template x-if="type === 'info'">
                    <svg class="w-5 h-5 {{ $colors['info']['icon_color'] }}" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                </template>
            </div>

            <!-- Message Content -->
            <div class="flex-1 pt-0.5">
                <h4 class="mb-1 text-sm font-semibold"
                    :class="{
                        '{{ $colors['success']['text'] }}': type === 'success',
                        '{{ $colors['error']['text'] }}': type === 'error',
                        '{{ $colors['warning']['text'] }}': type === 'warning',
                        '{{ $colors['info']['text'] }}': type === 'info'
                    }"
                    x-text="type === 'success' ? 'Berhasil!' : (type === 'error' ? 'Terjadi Kesalahan!' : (type === 'warning' ? 'Peringatan!' : 'Informasi'))">
                </h4>
                <p class="text-sm leading-5"
                    :class="{
                        '{{ $colors['success']['text'] }}': type === 'success',
                        '{{ $colors['error']['text'] }}': type === 'error',
                        '{{ $colors['warning']['text'] }}': type === 'warning',
                        '{{ $colors['info']['text'] }}': type === 'info'
                    }"
                    x-text="message">
                </p>
            </div>

            <!-- Close Button -->
            <button @click="hideMessage()"
                class="flex items-center justify-center flex-shrink-0 w-8 h-8 transition-colors duration-200 rounded-full hover:bg-white hover:bg-opacity-20 group">
                <svg class="w-4 h-4 text-gray-500 transition-colors duration-200 group-hover:text-gray-700"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="relative w-full h-1 overflow-hidden bg-black bg-opacity-10">
        <div class="h-full transition-all duration-75 ease-linear"
            :class="{
                '{{ $colors['success']['progress'] }}': type === 'success',
                '{{ $colors['error']['progress'] }}': type === 'error',
                '{{ $colors['warning']['progress'] }}': type === 'warning',
                '{{ $colors['info']['progress'] }}': type === 'info'
            }"
            :style="`width: ${progress}%`">
        </div>
    </div>
</div>
