@props([
    'title' => 'Judul Default',
    'icon' => 'fa-solid fa-box-open',
    'link' => '#',
    'linkText' => 'Lihat Detail',
    'headerColor' => 'bg-blue-500',
    'bodyColor' => 'bg-yellow-300',
    'linkColor' => 'bg-purple-600',
])

<a href="{{ $link }}" wire:navigate
    class="flex flex-col justify-between h-full overflow-hidden transition-all duration-300 border shadow-lg rounded-2xl hover:shadow-xl hover:-translate-y-1">

    {{-- Header Kartu --}}
    <div class="{{ $headerColor }} p-3 sm:p-4 text-center">
        <h3 class="text-base font-bold tracking-wide text-white uppercase sm:text-lg leading-snug line-clamp-2">
            {{ $title }}
        </h3>
    </div>

    {{-- Badan Kartu dengan Ikon --}}
    <div class="{{ $bodyColor }} flex-1 p-6 sm:p-8 flex justify-center items-center relative">
        {{-- Elemen dekoratif di belakang ikon --}}
        <div
            class="absolute text-2xl text-black -translate-x-10 -translate-y-6 opacity-10 sm:text-3xl sm:-translate-x-16 sm:-translate-y-8 rotate-12">
            <i class="fa-solid fa-ellipsis"></i>
        </div>
        <div
            class="absolute text-2xl text-black translate-x-10 translate-y-6 opacity-10 sm:text-3xl sm:translate-x-16 sm:translate-y-8 -rotate-12">
            <i class="fa-solid fa-ellipsis"></i>
        </div>

        {{-- Ikon Utama --}}
        <i class="{{ $icon }} text-5xl sm:text-7xl text-gray-800 opacity-75 z-10"></i>
    </div>

    {{-- Footer/Tombol Kartu --}}
    <div class="{{ $linkColor }} p-3 sm:p-4 text-center">
        <span class="text-sm font-bold tracking-wide text-white uppercase">
            {{ $linkText }}
        </span>
    </div>
</a>
