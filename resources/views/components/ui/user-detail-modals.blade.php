@props(['user' => null])

<div x-data="{ show: false }" x-on:open-user-detail-modal.window="show = true"
    x-on:close-user-detail-modal.window="show = false" x-on:keydown.escape.window="show = false" x-show="show"
    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display: none;">
    <div @click.away="show = false" class="w-full max-w-lg p-6 mx-4 bg-white rounded-lg shadow-xl" x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">
        {{-- Tampilkan konten hanya jika ada data user --}}
        @if ($user)
            <h2 class="pb-2 text-2xl font-bold border-b">Detail Pengguna: {{ $user->name }}</h2>

            <div class="mt-4 space-y-3">
                {{-- Foto --}}
                <div class="flex items-center space-x-4">
                    <img src="{{ $user->profile_picture }}" alt="{{ $user->name }}"
                        class="object-cover w-16 h-16 rounded-full">
                </div>
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
                        <span
                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </span>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <span class="font-medium text-gray-600">Tanggal Dibuat:</span>
                    <span class="col-span-2 text-gray-800">{{ $user->created_at->format('d F Y H:i') }}</span>
                </div>
            </div>

            <div class="flex justify-end pt-4 mt-4 border-t">
                <button @click="show = false" type="button"
                    class="px-4 py-2 font-medium text-white bg-gray-500 rounded-md hover:bg-gray-600">
                    Tutup
                </button>
            </div>
        @else
            {{-- Tampilan saat data sedang dimuat --}}
            <p>Memuat data pengguna...</p>
        @endif
    </div>
</div>
