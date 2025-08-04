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

<div x-data="{
    show: false,
    message: '',
    type: 'success',
    init() {
        // Check untuk session flash message saat komponen di-mount
        @if (session()->has('flash_message')) this.message = @js(session('flash_message.message'));
            this.type = @js(session('flash_message.type'));
            this.show = true;
            this.playNotificationSound(this.type);
            setTimeout(() => this.show = false, 5000); @endif

        // Juga check untuk flash message biasa Laravel
        @if (session()->has('success')) this.message = @js(session('success'));
            this.type = 'success';
            this.show = true;
            this.playNotificationSound('success');
            setTimeout(() => this.show = false, 5000); @endif

        @if (session()->has('error')) this.message = @js(session('error'));
            this.type = 'error';
            this.show = true;
            this.playNotificationSound('error');
            setTimeout(() => this.show = false, 5000); @endif

        @if (session()->has('warning')) this.message = @js(session('warning'));
            this.type = 'warning';
            this.show = true;
            this.playNotificationSound('warning');
            setTimeout(() => this.show = false, 5000); @endif
    },
    playNotificationSound(type = 'success') {
        try {
            const audioContext = new(window.AudioContext || window.webkitAudioContext)();

            let config = {};

            switch (type) {
                case 'success':
                    config = {
                        frequencies: [523.25, 659.25, 783.99], // C-E-G major chord
                        duration: 0.5,
                        volume: 0.3
                    };
                    break;
                case 'error':
                    config = {
                        frequencies: [415.30, 466.16], // G#-A# (dissonant)
                        duration: 0.8,
                        volume: 0.4,
                        type: 'sawtooth'
                    };
                    break;
                case 'warning':
                    config = {
                        frequencies: [440, 554.37], // A-C# (tritone)
                        duration: 0.6,
                        volume: 0.35
                    };
                    break;
            }

            config.frequencies.forEach((freq, index) => {
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();
                const filter = audioContext.createBiquadFilter();

                oscillator.connect(filter);
                filter.connect(gainNode);
                gainNode.connect(audioContext.destination);

                oscillator.type = config.type || 'sine';
                oscillator.frequency.setValueAtTime(freq, audioContext.currentTime);

                filter.type = 'lowpass';
                filter.frequency.setValueAtTime(3000, audioContext.currentTime);

                // Envelope untuk suara yang smooth
                gainNode.gain.setValueAtTime(0, audioContext.currentTime);
                gainNode.gain.linearRampToValueAtTime(config.volume, audioContext.currentTime + 0.02);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + config.duration);

                oscillator.start(audioContext.currentTime + (index * 0.08));
                oscillator.stop(audioContext.currentTime + config.duration + 0.1);
            });
        } catch (error) {
            // Jika audio context gagal, tidak masalah
            console.log('Audio notification disabled');
        }
    }
}"
    x-on:{{ $on }}.window="
        message = $event.detail.message || $event.detail[0]?.message || '';
        type = $event.detail.type || $event.detail[0]?.type || 'success';
        show = true;
        playNotificationSound(type);
        setTimeout(() => show = false, 5000)
    "
    x-show="show" x-transition:enter="transform ease-out duration-300 transition"
    x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" class="fixed z-50 max-w-sm p-4 border-l-4 rounded-md shadow-lg top-5 right-5"
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
