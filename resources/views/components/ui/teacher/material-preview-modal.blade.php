@props([
    'title' => '',
    'description' => '',
    'content' => '',
    'subject' => '',
    'class' => '',
])

<div x-data="{ show: false }" x-on:open-material-preview-modal.window="show = true"
    x-on:close-material-preview-modal.window="show = false" x-on:keydown.escape.window="show = false" x-show="show"
    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display: none;">
    <div @click.away="show = false"
        class="w-full max-w-3xl p-6 mx-4 bg-white rounded-lg shadow-xl max-h-[90vh] flex flex-col" x-show="show">
        {{-- Header Modal --}}
        <div class="pb-2 border-b">
            <h2 class="text-2xl font-bold text-gray-800">{{ $title }}</h2>
            <div class="text-sm text-gray-500">
                <span>{{ $subject }}</span> |
                <span>Kelas {{ $class }}</span>
            </div>
        </div>

        {{-- Konten Materi --}}
        <div class="flex-grow mt-4 space-y-4 overflow-y-auto">
            <div>
                <h3 class="font-bold text-gray-700">Deskripsi Singkat</h3>
                <p class="text-gray-600">{{ $description }}</p>
            </div>
            <hr>
            <div>
                <h3 class="font-bold text-gray-700">Konten Lengkap</h3>
                <div class="prose max-w-none trix-content">
                    {!! $content !!}
                </div>
            </div>
        </div>

        {{-- Footer Modal --}}
        <div class="flex justify-end pt-4 mt-4 border-t">
            <button @click="show = false" type="button"
                class="px-4 py-2 font-medium text-white bg-gray-500 rounded-md hover:bg-gray-600">
                Tutup
            </button>
        </div>
    </div>
</div>
