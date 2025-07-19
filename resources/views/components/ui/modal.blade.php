{{-- resources/views/components/ui/modal.blade.php --}}
@props([
    'show' => false,
    'maxWidth' => '2xl',
    'closeable' => true,
    'title' => 'Judul Modal',
    'confirmText' => 'Konfirmasi',
    'cancelText' => 'Batal',
    'wireConfirm' => null,
    'wireCancel' => null,
])

<div x-data="{
    show: @entangle($attributes->wire('model')).live,
    focusables() {
        let selector = 'a, button, input:not([type=\'hidden\']), textarea, select, details, [tabindex]:not([tabindex=\'-1\'])';
        return [...$el.querySelectorAll(selector)]
            .filter(el => !el.hasAttribute('disabled'));
    },
    firstFocusable() { return this.focusables()[0]; },
    lastFocusable() { return this.focusables().slice(-1)[0]; },
    init() {
        $watch('show', value => {
            if (value) {
                document.body.classList.add('overflow-y-hidden');
                {{ $attributes->has('focusable') ? 'setTimeout(() => this.firstFocusable()?.focus(), 100)' : '' }}
            } else {
                document.body.classList.remove('overflow-y-hidden');
            }
        });
    }
}" x-init="init()" x-show="show" class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;" x-cloak>

    {{-- Overlay --}}
    <div x-show="show" class="fixed inset-0 transform transition-all" x-on:click="show = false"
        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>

    {{-- Modal Panel --}}
    <div x-show="show" class="fixed inset-0 transform flex items-center justify-center p-4"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        @if ($closeable) x-on:keydown.escape.window="show = false" @endif>

        <div
            {{ $attributes->merge(['class' => 'bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-' . $maxWidth]) }}>
            {{-- Modal Header --}}
            <div class="bg-gray-100 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">{{ $title }}</h3>
                @if ($closeable)
                    <button type="button" x-on:click="show = false"
                        class="text-gray-400 hover:text-gray-600 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endif
            </div>

            {{-- Modal Body --}}
            <div class="px-6 py-4">
                {{ $slot }}
            </div>

            {{-- Modal Footer / Action Buttons --}}
            <div class="px-6 py-4 bg-gray-100 text-right space-x-2">
                @if ($wireConfirm)
                    <button type="button" wire:click="{{ $wireConfirm }}" x-on:click="show = false"
                        class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        {{ $confirmText }}
                    </button>
                @endif

                <button type="button"
                    x-on:click="show = false; @if ($wireCancel) $wire.{{ $wireCancel }}(); @endif"
                    class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 border border-transparent rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    {{ $cancelText }}
                </button>
            </div>
        </div>
    </div>
</div>
