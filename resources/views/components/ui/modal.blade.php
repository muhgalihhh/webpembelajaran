@props(['id'])

<div x-data="{ open: false }" x-show="open"
    @open-modal.window="if ($event.detail.id === '{{ $id }}') open = true" @close-modal.window="open = false"
    style="display: none;"
    class="fixed inset-0 z-50 flex items-start justify-center p-4 overflow-y-auto border border-black"
    aria-labelledby="modal-title" role="dialog" aria-modal="true">

    {{-- Latar Belakang Overlay --}}
    <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 transition-opacity " style="background-color: rgba(0, 0, 0, 0.5);"></div>

    {{-- Konten Modal --}}
    <div x-show="open" @click.away="open = false" x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="relative flex flex-col w-full max-w-2xl my-8 overflow-hidden transition-all transform bg-white border border-black rounded-lg shadow-xl">

        {{-- Area Konten Utama (dengan scroll) --}}
        <div class="flex-grow p-6 overflow-y-auto">
            {{ $slot }}
        </div>
    </div>
</div>
