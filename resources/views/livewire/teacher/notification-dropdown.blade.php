<div x-data="{
    notificationOpen: false,
    unreadCount: @entangle('notificationCount').live
}" @click.away="notificationOpen = false" class="relative">

    {{-- Tombol Notifikasi --}}
    <button @click="notificationOpen = !notificationOpen"
        class="relative px-3 py-2 text-gray-600 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        <i class="fas fa-bell"></i>
        {{-- Badge ini sekarang akan reaktif dan menampilkan angka dengan benar --}}
        <template x-if="unreadCount > 0">
            <span
                class="absolute top-0 right-0 flex items-center justify-center w-5 h-5 text-xs font-bold text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 border-2 border-white rounded-full"
                x-text="unreadCount">
            </span>
        </template>
    </button>

    {{-- Menu Dropdown Notifikasi --}}
    <div x-show="notificationOpen" x-cloak x-transition
        class="absolute right-0 z-50 flex flex-col mt-2 bg-white border border-gray-200 rounded-lg shadow-xl w-80 sm:w-96 top-full">

        <div class="px-4 py-3 border-b">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-gray-800">Notifikasi</h3>
                @if ($this->unreadCount > 0)
                    <button wire:click="markAllAsRead"
                        class="text-xs font-semibold text-indigo-600 hover:underline focus:outline-none">
                        Tandai semua dibaca
                    </button>
                @endif
            </div>
        </div>

        <div class="flex p-1 bg-gray-100 border-b">
            <button wire:click.prevent="setFilter('all')"
                class="flex items-center justify-center w-full gap-2 px-2 py-1 text-xs rounded-md focus:outline-none {{ $filter === 'all' ? 'font-bold bg-white shadow text-indigo-600' : 'text-gray-600 hover:bg-gray-200' }}">
                Semua
                @if ($this->unreadCount > 0)
                    <span
                        class="flex items-center justify-center w-4 h-4 text-white bg-gray-400 rounded-full text-[10px]">{{ $this->unreadCount }}</span>
                @endif
            </button>
            <button wire:click.prevent="setFilter('task')"
                class="flex items-center justify-center w-full gap-2 px-2 py-1 text-xs rounded-md focus:outline-none {{ $filter === 'task' ? 'font-bold bg-white shadow text-indigo-600' : 'text-gray-600 hover:bg-gray-200' }}">
                Tugas
                @if ($this->unreadTaskCount > 0)
                    <span
                        class="flex items-center justify-center w-4 h-4 text-white bg-sky-500 rounded-full text-[10px]">{{ $this->unreadTaskCount }}</span>
                @endif
            </button>
            <button wire:click.prevent="setFilter('quiz')"
                class="flex items-center justify-center w-full gap-2 px-2 py-1 text-xs rounded-md focus:outline-none {{ $filter === 'quiz' ? 'font-bold bg-white shadow text-indigo-600' : 'text-gray-600 hover:bg-gray-200' }}">
                Kuis
                @if ($this->unreadQuizCount > 0)
                    <span
                        class="flex items-center justify-center w-4 h-4 text-white bg-amber-500 rounded-full text-[10px]">{{ $this->unreadQuizCount }}</span>
                @endif
            </button>
        </div>

        <div class="flex-grow overflow-y-auto max-h-80" wire:key="notification-list-{{ $filter }}">
            @forelse ($this->notifications as $notification)
                @php
                    $data = $notification->data;
                    $type = $data['type'] ?? 'Umum';
                    $icon = 'fa-bell';
                    $iconColor = 'bg-gray-400';
                    if (Str::contains($type, 'Tugas')) {
                        $icon = 'fa-clipboard-check';
                        $iconColor = 'bg-sky-500';
                    } elseif (Str::contains($type, 'Kuis')) {
                        $icon = 'fa-spell-check';
                        $iconColor = 'bg-amber-500';
                    }
                @endphp
                <a href="#" wire:click.prevent="markAsReadAndRedirect('{{ $notification->id }}')"
                    class="flex items-start px-4 py-3 transition-colors duration-200 border-b border-gray-100 last:border-b-0 hover:bg-gray-100 @if (is_null($notification->read_at)) bg-indigo-50 @endif">
                    <div class="flex-shrink-0 mr-4">
                        <div
                            class="flex items-center justify-center w-10 h-10 text-white rounded-full {{ $iconColor }}">
                            <i class="fas {{ $icon }}"></i>
                        </div>
                    </div>
                    <div class="flex-grow">
                        <p class="text-sm font-semibold text-gray-800">{{ $data['title'] ?? 'Notifikasi' }}</p>
                        <p class="text-xs text-gray-600">{{ $data['message'] ?? 'Ada aktivitas baru dari siswa.' }}</p>
                        <p class="mt-1 text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                    @if (is_null($notification->read_at))
                        <div class="flex-shrink-0 ml-2">
                            <div class="w-2.5 h-2.5 mt-1 bg-indigo-500 rounded-full" title="Baru"></div>
                        </div>
                    @endif
                </a>
            @empty
                <div class="px-4 py-8 text-center">
                    <i class="mb-2 text-4xl text-gray-300 fas fa-check-circle"></i>
                    <p class="text-sm text-gray-500">Tidak ada notifikasi untuk filter ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
