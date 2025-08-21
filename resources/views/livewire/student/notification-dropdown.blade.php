<div x-data="{
    notificationOpen: false,
    unreadCount: @entangle('notificationCount').live
}" @click.away="notificationOpen = false" class="relative">

    <button @click="notificationOpen = !notificationOpen"
        class="relative px-3 py-2 text-gray-600 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        <i class="fas fa-bell"></i>

        <template x-if="unreadCount > 0">
            <span
                class="absolute top-0 right-0 flex items-center justify-center w-5 h-5 text-xs font-bold text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 border-2 border-white rounded-full"
                x-text="unreadCount">
            </span>
        </template>
    </button>

    {{-- Menu Dropdown Notifikasi --}}
    <div x-show="notificationOpen" x-cloak x-transition:enter="transition ease-out duration-200 transform"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150 transform" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed sm:absolute z-50 flex flex-col mt-2 bg-white border border-gray-200 rounded-lg shadow-xl top-16 sm:top-full right-4 sm:right-0 w-[calc(100vw-2rem)] max-w-sm sm:w-96"
        style="max-height: calc(100vh - 5rem);">

        <div class="px-4 py-3 border-b">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-gray-800">Notifikasi</h3>
                @if ($unreadCount > 0)
                    <button wire:click="markAllAsRead"
                        class="hidden text-xs font-semibold text-indigo-600 hover:underline focus:outline-none sm:block">
                        Tandai semua dibaca
                    </button>
                    <button wire:click="markAllAsRead"
                        class="p-1 text-xs font-semibold text-indigo-600 bg-indigo-100 rounded-full hover:underline focus:outline-none sm:hidden">
                        <i class="fas fa-check"></i>
                    </button>
                @endif
            </div>
        </div>

        <div class="flex p-1 bg-gray-100 border-b">
            <button wire:click.prevent="setFilter('all')"
                class="flex items-center justify-center w-full gap-1 px-2 py-1 text-xs rounded-md focus:outline-none {{ $filter === 'all' ? 'font-bold bg-white shadow text-indigo-600' : 'text-gray-600 hover:bg-gray-200' }}">
                <span class="hidden sm:inline">Semua</span>
                <span class="sm:hidden">All</span>
                @if ($unreadCount > 0)
                    <span
                        class="flex items-center justify-center w-4 h-4 text-white bg-gray-400 rounded-full text-[10px]">{{ $unreadCount }}</span>
                @endif
            </button>
            <button wire:click.prevent="setFilter('new')"
                class="flex items-center justify-center w-full gap-1 px-2 py-1 text-xs rounded-md focus:outline-none {{ $filter === 'new' ? 'font-bold bg-white shadow text-indigo-600' : 'text-gray-600 hover:bg-gray-200' }}">
                <span class="hidden sm:inline">Baru</span>
                <span class="sm:hidden">New</span>
                @if ($unreadNewCount > 0)
                    <span
                        class="flex items-center justify-center w-4 h-4 text-white bg-blue-500 rounded-full text-[10px]">{{ $unreadNewCount }}</span>
                @endif
            </button>
            <button wire:click.prevent="setFilter('updated')"
                class="flex items-center justify-center w-full gap-1 px-2 py-1 text-xs rounded-md focus:outline-none {{ $filter === 'updated' ? 'font-bold bg-white shadow text-indigo-600' : 'text-gray-600 hover:bg-gray-200' }}">
                <span class="hidden sm:inline">Update</span>
                <span class="sm:hidden">Updt</span>
                @if ($unreadUpdatedCount > 0)
                    <span
                        class="flex items-center justify-center w-4 h-4 text-white bg-green-500 rounded-full text-[10px]">{{ $unreadUpdatedCount }}</span>
                @endif
            </button>
        </div>

        <div class="flex-grow overflow-y-auto" style="max-height: calc(100vh - 12rem);"
            wire:key="notification-list-{{ $filter }}">
            @forelse ($notifications as $notification)
                @php
                    $data = $notification->data;
                    $icon = 'fa-bell';
                    $iconColor = 'bg-gray-400';
                    if (Str::contains($data['type'], 'Materi')) {
                        $icon = 'fa-book-open';
                        $iconColor = 'bg-indigo-500';
                    } elseif (Str::contains($data['type'], 'Kuis')) {
                        $icon = 'fa-pencil-ruler';
                        $iconColor = 'bg-amber-500';
                    } elseif (Str::contains($data['type'], 'Tugas')) {
                        $icon = 'fa-clipboard-list';
                        $iconColor = 'bg-sky-500';
                    }
                @endphp
                <a href="#" wire:click.prevent="markAsReadAndRedirect('{{ $notification->id }}')"
                    class="flex items-start px-4 py-3 transition-colors duration-200 border-b border-gray-100 last:border-b-0 hover:bg-gray-100 @if (is_null($notification->read_at)) bg-indigo-50 @endif">
                    <div class="flex-shrink-0 mr-3">
                        <div
                            class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 text-white rounded-full {{ $iconColor }}">
                            <i class="text-sm fas {{ $icon }} sm:text-base"></i>
                        </div>
                    </div>
                    <div class="flex-grow min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate sm:text-base">
                            {{ $data['title'] ?? 'Notifikasi Baru' }}</p>
                        <p class="text-xs text-gray-600 line-clamp-2 sm:text-sm">
                            {{ $data['message'] ?? 'Ada konten baru untukmu.' }}</p>
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
                    <i class="mb-2 text-3xl text-gray-300 fas fa-check-circle sm:text-4xl"></i>
                    <p class="text-sm text-gray-500">Tidak ada notifikasi untuk filter ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
