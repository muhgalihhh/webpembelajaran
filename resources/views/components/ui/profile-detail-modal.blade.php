@props(['user' => null])

<div x-data="{ show: false }" x-on:open-user-detail-modal.window="show = true"
    x-on:close-user-detail-modal.window="show = false" x-on:keydown.escape.window="show = false" x-show="show"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display: none;">
    <div @click.away="show = false" class="w-full max-w-lg p-6 mx-4 bg-white rounded-lg shadow-xl" x-show="show"
        x-transition>
        @if ($user)
            {{-- Header Modal --}}
            <h2 class="pb-2 text-2xl font-bold border-b">Detail Pengguna: {{ $user->name }}</h2>

            {{-- Konten Detail --}}
            <div class="mt-4 space-y-3">
                <div class="grid grid-cols-3 gap-4">
                    <span class="font-medium text-gray-600">Nama Lengkap:</span>
                    <span class="col-span-2 text-gray-800">{{ $user->name }}</span>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <span class="font-medium text-gray-600">Username:</span>
                    <span class="col-span-2 text-gray-800">{{ $user->username }}</span>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <span class="font-medium text-gray-600">Email:</span>
                    <span class="col-span-2 text-gray-800">{{ $user->email }}</span>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <span class="font-medium text-gray-600">No. Telepon:</span>
                    <span class="col-span-2 text-gray-800">{{ $user->phone_number ?? '-' }}</span>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <span class="font-medium text-gray-600">Status:</span>
                    <span class="col-span-2">
                        <span @class([
                            'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                            'bg-green-100 text-green-800' => $user->status == 'active',
                            'bg-red-100 text-red-800' => $user->status == 'inactive',
                        ])>
                            {{ ucfirst($user->status) }}
                        </span>
                    </span>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <span class="font-medium text-gray-600">Tanggal Dibuat:</span>
                    <span class="col-span-2 text-gray-800">{{ $user->created_at->format('d F Y H:i') }}</span>
                </div>
            </div>

            {{-- Footer dengan Tombol Edit --}}
            <div class="flex justify-end pt-4 mt-4 space-x-3 border-t">
                <button @click="show = false" type="button" class="btn btn-secondary">
                    Tutup
                </button>
                <button wire:click="editFromView({{ $user->id }})" type="button" class="btn btn-primary">
                    Edit Pengguna
                </button>
            </div>
        @else
            <p>Memuat data...</p>
        @endif
    </div>
</div>
