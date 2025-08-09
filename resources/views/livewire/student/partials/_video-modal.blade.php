@if ($selectedMaterial)
    <div x-data="{ show: @entangle('selectedMaterial').defer }" x-show="show" x-on:keydown.escape.window="$wire.closeModal()"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-75" x-transition.opacity>
        <div class="relative w-full max-w-4xl" @click.away="$wire.closeModal()">
            <div class="overflow-hidden bg-black rounded-lg aspect-w-16 aspect-h-9">
                <iframe src="{{ $selectedMaterial->embed_url ?? '' }}" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen class="w-full h-full"></iframe>
            </div>
            <button @click="$wire.closeModal()"
                class="absolute p-2 text-white bg-gray-800 rounded-full -top-3 -right-3 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
    </div>
@endif
