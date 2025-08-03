@props(['material', 'context' => 'text'])

<div
    class="flex flex-col p-4 transition-transform duration-200 ease-in-out bg-white border border-gray-500 rounded-lg shadow-[6px_6px_0px_rgba(0,0,0,0.2)] sm:p-6 hover:translate-y-1 ">

    {{-- [1] Tampilan Ikon/Thumbnail HANYA untuk layar besar (sm ke atas) --}}
    <div class="hidden sm:block">
        @if ($context === 'video' && isset($material->thumbnail_url))
            {{-- Tampilan Thumbnail YouTube --}}
            <div class="relative mb-4 overflow-hidden border-2 border-black rounded-lg cursor-pointer group"
                wire:click.prevent="selectMaterial({{ $material->id }})">
                <img src="{{ $material->thumbnail_url }}" alt="Thumbnail {{ $material->title }}"
                    class="object-cover w-full transition-transform duration-300 group-hover:scale-110">
                <div
                    class="absolute inset-0 flex items-center justify-center transition-opacity duration-300 bg-black opacity-100 bg-opacity-40 group-hover:opacity-100">
                    <div class="flex items-center justify-center w-16 h-16 bg-white rounded-full bg-opacity-90">
                        <i class="text-4xl text-blue-600 fas fa-play"></i>
                    </div>
                </div>
            </div>
        @elseif ($context === 'video')
            {{-- Fallback Ikon Video --}}
            <div class="relative p-8 mb-6 text-center bg-blue-100 border-2 border-blue-200 rounded-lg">
                <div
                    class="flex items-center justify-center w-16 h-16 mx-auto bg-blue-600 border-2 border-blue-700 rounded-full">
                    <i class="text-4xl text-white fas fa-video"></i>
                </div>
            </div>
        @else
            {{-- Tampilan Ikon PDF --}}
            <div class="text-center">
                <div class="relative p-8 mb-6 text-center bg-red-100 border-2 border-red-200 rounded-lg">
                    <div
                        class="flex items-center justify-center w-20 h-20 mx-auto bg-red-600 border-2 border-red-700 rounded-lg">
                        <i class="text-4xl text-white fas fa-file-pdf"></i>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- [2] Keterangan Tipe Konten HANYA untuk layar kecil (mobile) --}}
    <div class="mb-2 sm:hidden">
        @if ($context === 'video')
            <span
                class="inline-flex items-center px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">
                <i class="mr-1.5 fas fa-video"></i>
                Video
            </span>
        @else
            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">
                <i class="mr-1.5 fas fa-file-pdf"></i>
                PDF
            </span>
        @endif
    </div>

    {{-- [3] Konten Teks (Selalu Tampil) --}}
    <div class="flex-grow space-y-2">
        <h3 class="text-lg font-bold text-gray-900">{{ $material->title }}</h3>
        <p class="text-sm text-gray-600">{{ $material->description }}</p>
        @if ($material->page_count)
            <p class="text-xs text-gray-500">{{ $material->page_count }} Halaman</p>
        @else
            <p class="text-xs text-gray-500">Jumlah halaman tidak tersedia</p>
        @endif
    </div>

    {{-- Tombol Aksi (Selalu Tampil) --}}
    @if ($context === 'video')
        <button wire:click.prevent="selectMaterial({{ $material->id }})"
            class="block w-full py-3 mt-4 font-medium text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700">
            Tonton Video
        </button>
    @else
        <a href="" target="_blank" wire:click.prevent="recordAccess({{ $material->id }})"
            class="block w-full py-3 mt-4 font-medium text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700">
            Baca Materi
        </a>
    @endif
</div>
