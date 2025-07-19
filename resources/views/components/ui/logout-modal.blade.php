@props(['show' => false])

<div x-data="{ show: @entangle('showLogoutModal').live }" x-show="show" x-cloak x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto"
    @keydown.escape.window="$wire.cancelLogout()">
    {{-- Overlay --}}
    <div x-show="show" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
        @click="$wire.cancelLogout()"></div>

    {{-- Modal Panel --}}
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div x-show="show" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="bg-white rounded-lg shadow-xl max-w-sm mx-auto p-6 relative z-10" @click.stop>
            <h3 class="text-xl font-semibold text-gray-900 mb-4 text-center">Konfirmasi Logout</h3>
            <p class="text-gray-700 text-center mb-6">Apakah Anda yakin ingin keluar dari akun?</p>

            <div class="flex justify-end space-x-3">
                <button type="button" wire:click="cancelLogout"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                    Batal
                </button>
                <button type="button" wire:click="logoutUser"
                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                    Ya, Logout
                </button>
            </div>
        </div>
    </div>
</div>
