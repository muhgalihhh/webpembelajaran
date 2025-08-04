<div x-data="{ notificationOpen: false }" @click.away="notificationOpen = false" class="relative">
    {{-- Tombol Notifikasi dengan Design Modern --}}
    <button @click="notificationOpen = !notificationOpen"
        class="relative p-3 text-gray-600 transition-all duration-200 bg-white border border-gray-200 shadow-sm rounded-xl hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
            </path>
        </svg>

        {{-- Badge dengan Animasi --}}
        @if ($this->unreadCount > 0)
            <span
                class="absolute flex items-center justify-center h-5 px-1 text-xs font-bold text-white transform rounded-full -top-1 -right-1 min-w-5 bg-gradient-to-r from-red-500 to-pink-500 animate-pulse">
                {{ $this->unreadCount > 99 ? '99+' : $this->unreadCount }}
            </span>
        @endif
    </button>

    {{-- Menu Dropdown dengan Design Modern --}}
    <div x-show="notificationOpen" x-cloak x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
        class="absolute right-0 z-50 mt-3 bg-white border border-gray-200 shadow-2xl rounded-2xl w-96 top-full backdrop-blur-sm">

        {{-- Header dengan Gradient --}}
        <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-purple-600 rounded-t-2xl">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-white">Notifikasi</h3>
                @if ($this->unreadCount > 0)
                    <button wire:click="markAllAsRead"
                        class="px-3 py-1 text-sm font-medium text-blue-100 transition-colors duration-200 bg-white rounded-lg bg-opacity-20 hover:bg-opacity-30">
                        Tandai Semua Dibaca
                    </button>
                @endif
            </div>
            @if ($this->unreadCount > 0)
                <p class="mt-1 text-sm text-blue-100">{{ $this->unreadCount }} notifikasi belum dibaca</p>
            @endif
        </div>

        {{-- List Notifikasi --}}
        <div class="py-2 overflow-y-auto max-h-96">
            @forelse ($this->notifications as $notification)
                @php
                    // Handle both array and object notation
                    $notificationId = is_array($notification) ? $notification['id'] : $notification->id;
                    $notificationData = is_array($notification) ? $notification['data'] : $notification->data;
                    $readAt = is_array($notification) ? $notification['read_at'] : $notification->read_at;
                    $createdAt = is_array($notification) ? $notification['created_at'] : $notification->created_at;

                    // Convert string to Carbon if needed
                    if (is_string($createdAt)) {
                        $createdAt = \Carbon\Carbon::parse($createdAt);
                    }
                @endphp

                <div wire:click="markAsReadAndRedirect('{{ $notificationId }}')"
                    class="flex items-start px-6 py-4 transition-all duration-200 cursor-pointer group hover:bg-gray-50 border-l-4
                            {{ is_null($readAt) ? 'border-l-blue-500 bg-blue-50/50' : 'border-l-transparent' }}">

                    {{-- Icon berdasarkan tipe notifikasi --}}
                    <div class="flex-shrink-0 mr-4">
                        @if (($notificationData['type'] ?? '') === 'Materi Baru')
                            <div
                                class="flex items-center justify-center w-10 h-10 shadow-md bg-gradient-to-r from-green-400 to-emerald-500 rounded-xl">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        @elseif(($notificationData['type'] ?? '') === 'Tugas Baru')
                            <div
                                class="flex items-center justify-center w-10 h-10 shadow-md bg-gradient-to-r from-yellow-400 to-orange-500 rounded-xl">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                    <path fill-rule="evenodd"
                                        d="M4 5a2 2 0 012-2v1a1 1 0 001 1h6a1 1 0 001-1V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm8 8a1 1 0 100-2 1 1 0 000 2z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        @elseif(($notificationData['type'] ?? '') === 'Kuis Baru')
                            <div
                                class="flex items-center justify-center w-10 h-10 shadow-md bg-gradient-to-r from-purple-400 to-pink-500 rounded-xl">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        @else
                            <div
                                class="flex items-center justify-center w-10 h-10 shadow-md bg-gradient-to-r from-blue-400 to-indigo-500 rounded-xl">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    {{-- Konten Notifikasi --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p
                                    class="text-sm font-semibold text-gray-900 transition-colors duration-200 group-hover:text-blue-600">
                                    {{ $notificationData['type'] ?? 'Notifikasi' }}
                                </p>
                                <p class="mt-1 text-sm text-gray-600 line-clamp-2">
                                    <span
                                        class="font-medium">{{ $notificationData['title'] ?? 'Ada konten baru' }}</span>
                                    @if ($notificationData['subject_name'] ?? false)
                                        di mapel <span
                                            class="font-medium text-blue-600">{{ $notificationData['subject_name'] }}</span>
                                    @endif
                                </p>

                                {{-- Time dengan styling yang better --}}
                                <div class="flex items-center mt-2 text-xs text-gray-500">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $createdAt->diffForHumans() }}
                                </div>
                            </div>

                            {{-- Unread indicator --}}
                            @if (is_null($readAt))
                                <div class="w-3 h-3 mt-1 ml-2 bg-blue-500 rounded-full animate-pulse"></div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center px-6 py-12">
                    <div class="flex items-center justify-center w-16 h-16 mb-4 bg-gray-100 rounded-2xl">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                    </div>
                    <p class="mb-1 text-sm font-medium text-gray-900">Tidak ada notifikasi</p>
                    <p class="text-xs text-center text-gray-500">Notifikasi baru akan muncul di sini</p>
                </div>
            @endforelse
        </div>

        {{-- Footer dengan Action Button --}}
        @if ($this->notifications->isNotEmpty())
            <div class="px-6 py-3 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
                <a href="#"
                    class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-blue-600 transition-colors duration-200 bg-white border border-blue-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Lihat Semua Notifikasi
                </a>
            </div>
        @endif
    </div>

    <style>
        /* Custom animations dan styles */
        @keyframes notification-bounce {

            0%,
            20%,
            53%,
            80%,
            100% {
                transform: translate3d(0, 0, 0);
            }

            40%,
            43% {
                transform: translate3d(0, -8px, 0);
            }

            70% {
                transform: translate3d(0, -4px, 0);
            }

            90% {
                transform: translate3d(0, -2px, 0);
            }
        }

        .animate-notification-bounce {
            animation: notification-bounce 1s ease-in-out;
        }

        /* Line clamp untuk text truncation */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Smooth scroll untuk dropdown */
        .max-h-96 {
            scrollbar-width: thin;
            scrollbar-color: rgb(203 213 225) transparent;
        }

        .max-h-96::-webkit-scrollbar {
            width: 6px;
        }

        .max-h-96::-webkit-scrollbar-track {
            background: transparent;
        }

        .max-h-96::-webkit-scrollbar-thumb {
            background-color: rgb(203 213 225);
            border-radius: 3px;
        }

        .max-h-96::-webkit-scrollbar-thumb:hover {
            background-color: rgb(148 163 184);
        }
    </style>
</div>
