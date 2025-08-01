<div x-data="{ notificationOpen: false }" @click.away="notificationOpen = false" class="relative">
    {{-- Tombol Notifikasi --}}
    <button @click="notificationOpen = !notificationOpen"
        class="relative px-3 py-2 text-black bg-white border rounded-md hover:text-gray-200">
        <i class="fas fa-bell"></i>
        @if ($unreadCount > 0)
            <span
                class="absolute top-0 right-0 block w-4 h-4 text-xs text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">
                {{ $unreadCount }}
            </span>
        @endif
    </button>

    {{-- Menu Dropdown Notifikasi --}}
    <div x-show="notificationOpen" x-cloak x-transition:enter="transition ease-out duration-200 transform"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150 transform"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
        class="absolute right-0 z-50 w-64 mt-2 bg-white border border-gray-200 rounded-lg shadow-lg top-full">

        <div class="px-4 py-2 font-bold text-gray-700 border-b">
            Notifikasi
        </div>

        <div class="py-1">
            @forelse ($this->notifications as $notification)
                <a href="{{ $notification->data['link'] ?? '#' }}"
                    wire:click.prevent="markAsRead('{{ $notification->id }}')"
                    class="block px-4 py-2 text-sm text-gray-700 transition-colors duration-200 hover:bg-gray-100 @if (is_null($notification->read_at)) bg-blue-50 @endif">
                    {{-- Mengakses data dari kolom 'data' --}}
                    <p class="font-semibold">{{ $notification->data['title'] }}</p>
                    <p class="text-xs text-gray-500">{{ $notification->data['message'] }}</p>
                    <p class="mt-1 text-xs text-right text-gray-400">
                        {{ $notification->created_at->diffForHumans() }}
                    </p>
                </a>
            @empty
                <div class="px-4 py-3 text-sm text-center text-gray-500">
                    Tidak ada notifikasi baru.
                </div>
            @endforelse
        </div>

        <div class="py-1 text-center border-t">
            <a href="#" class="text-sm text-blue-600 hover:underline">Lihat Semua Notifikasi</a>
        </div>
    </div>
</div>
