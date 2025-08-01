@props(['material'])

<div class="max-w-md p-6 bg-white border border-blue-300 rounded-lg">
    <div wire:key="last-access-{{ $material->id }}" class="flex items-center space-x-4">
        {{-- Ikon --}}
        <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 bg-blue-100 rounded">
            @if (!empty($material->youtube_url))
                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z" />
                </svg>
            @else
                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2h12v8H4V6z" />
                </svg>
            @endif
        </div>

        {{-- Konten Teks --}}
        <div class="flex-1">
            <h4 class="font-semibold text-gray-900">{{ $material->title }}</h4>
            <p class="text-sm text-gray-500">
                Diakses
                {{ \Carbon\Carbon::parse($material->pivot->accessed_at ?? $material->accessed_at)->locale('id')->diffForHumans() }}
            </p>
        </div>

        {{-- Tombol Aksi (Link) --}}
        <a href="" target="_blank" wire:click.prevent="recordAccess({{ $material->id }})"
            class="px-4 py-2 text-sm font-medium text-gray-800 bg-gray-200 rounded hover:bg-gray-300">
            Buka Lagi
        </a>
    </div>
</div>
