<div x-data="{ open: @js($show), modalType: @js($type) }" x-show="open" x-on:close-modal.window="open = false; modalType = ''"
    x-on:keydown.escape.window="open = false; modalType = ''" x-on:click.self="open = false; modalType = ''"
    class="fixed inset-0 z-[9999] flex items-center justify-center bg-gray-900 bg-opacity-50">

    {{-- modal content --}}
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md m-auto">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">
                @if ($type === 'logout')
                    Konfirmasi Logout
                @else
                    Pemberitahuan
                @endif
            </h2>
            <button @click="open = false" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="mb-4 text-gray-700">
            @if ($type === 'logout')
                <p>Apakah Anda yakin ingin keluar dari akun ini?</p>
            @else
                <p>This is a modal dialog. You can put any content here.</p>
            @endif
        </div>
        <div class="mt-4 flex justify-end space-x-3">
            @if ($type === 'logout')
                <button @click="open = false" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
                    Batal
                </button>
                <button wire:click="logoutUser" @click="open = false"
                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    Ya, Keluar
                </button>
            @else
                <button @click="open = false" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Close
                </button>
            @endif
        </div>
    </div>
</div>
