<div x-data="{ notificationOpen: false }" @click.away="notificationOpen = false" class="relative">
    {{-- Tombol Notifikasi --}}
    <button @click="notificationOpen = !notificationOpen"
        class="relative px-3 py-2 text-black bg-white border rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
        <i class="fas fa-bell"></i>
        {{-- Badge untuk notifikasi yang belum dibaca --}}
        @if ($this->unreadCount > 0)
            <span
                class="absolute top-0 right-0 flex items-center justify-center w-4 h-4 text-xs font-bold text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                {{ $this->unreadCount }}
            </span>
        @endif
    </button>

    {{-- Menu Dropdown Notifikasi --}}
    <div x-show="notificationOpen" x-cloak x-transition:enter="transition ease-out duration-200 transform"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150 transform" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 z-50 mt-2 bg-white border border-gray-200 rounded-lg shadow-lg w-80 top-full">

        <div class="px-4 py-2 font-bold text-gray-800 border-b">
            Notifikasi
        </div>

        <div class="py-1 overflow-y-auto max-h-96">
            @forelse ($this->notifications as $notification)
                <a href="#" {{-- [MODIFIKASI 1] Panggil metode baru untuk menandai dan mengarahkan --}}
                    wire:click.prevent="markAsReadAndRedirect('{{ $notification->id }}')"
                    class="flex items-start px-4 py-3 text-sm text-gray-700 transition-colors duration-200 hover:bg-gray-100
                          {{-- Sorot notifikasi yang belum dibaca --}}
                          @if (is_null($notification->read_at)) bg-blue-50 hover:bg-blue-100 @endif">

                    {{-- [MODIFIKASI 2] Tampilan notifikasi yang lebih kaya informasi --}}
                    <div class="pt-1 mr-3 text-blue-500">
                        <i class="fa-solid fa-circle-info fa-lg"></i>
                    </div>
                    <div class="flex-grow">
                        <p class="font-bold text-gray-800">{{ $notification->data['type'] ?? 'Notifikasi' }}</p>
                        <p class="text-gray-700">
                            <span class="font-semibold">{{ $notification->data['title'] ?? 'Ada konten baru' }}</span>
                            di mapel {{ $notification->data['subject_name'] ?? '' }}.
                        </p>
                        <p class="mt-1 text-xs text-right text-gray-400">
                            {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>
                </a>
            @empty
                <div class="px-4 py-3 text-sm text-center text-gray-500">
                    Tidak ada notifikasi baru.
                </div>
            @endforelse
        </div>

        <div class="py-1 text-center border-t">
            {{-- Ganti '#' dengan route halaman semua notifikasi jika sudah ada --}}
            <a href="#" class="text-sm font-semibold text-blue-600 hover:underline">Lihat Semua</a>
        </div>
    </div>
</div>
