@props(['material'])

<div
    class="w-full max-w-md p-4 transition-colors duration-200 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 hover:bg-gray-50">
    <div wire:key="last-access-{{ $material->id }}" class="flex items-center space-x-4">
        {{-- Icon --}}
        <div class="flex-shrink-0">
            @if ($material->youtube_url)
                <svg class="w-8 h-8 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Zm.008 3.75h.008v.008h-.008V12Zm0 3.75h.008v.008h-.008v-3.75Z" />
                </svg>
            @else
                <svg class="w-8 h-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
            @endif
        </div>

        {{-- Text Content --}}
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-gray-900 truncate">
                {{ $material->title }}
            </p>
            <p class="text-sm text-gray-500 truncate">
                Diakses
                {{-- Ini adalah baris yang diperbaiki --}}
                <span
                    class="font-medium">{{ \Carbon\Carbon::parse($material->pivot->accessed_at)->locale('id')->diffForHumans() }}</span>
            </p>
        </div>

        {{-- Action Button (Link) --}}
        <div class="inline-flex items-center">
            <a href="" wire:navigate
                class="px-3 py-2 text-sm font-medium text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300">
                Lihat
            </a>
        </div>
    </div>
</div>
