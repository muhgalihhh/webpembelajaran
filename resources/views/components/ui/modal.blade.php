<div x-data="{ show: false }" x-show="show" x-on:open-modal.window="show = true" x-on:close-modal.window="show = false"
    x-on:keydown.escape.window="show = false" x-on:click.self="show = false" {{-- TAMBAHKAN INI --}}
    class="fixed inset-0 z-[9999] flex items-center justify-center bg-gray-900 bg-opacity-50">


    {{-- modal content --}}
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md m-auto">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold"></h2>
            <button @click="show = false" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="mb-4 text-gray-700">
            <p class="text-gray-700">This is a modal dialog. You can put any content here.</p>
        </div>
        <div class="mt-4 flex justify-end">
            <button @click="show = false" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                Close
            </button>
        </div>
    </div>
</div>
