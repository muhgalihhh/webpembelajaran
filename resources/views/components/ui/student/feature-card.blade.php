@props([
    'title' => 'Judul Default',
    'icon' => 'fa-solid fa-box-open', // Ikon default
    'link' => '#',
    'linkText' => 'Lihat Detail',
    'headerColor' => 'bg-blue-500',
    'bodyColor' => 'bg-yellow-300',
    'linkColor' => 'bg-purple-600',
])

<a href="{{ $link }}" wire:navigate
    class="{{ $bodyColor }} block overflow-hidden transition-all duration-300 border shadow-lg rounded-2xl hover:shadow-xl hover:-translate-y-1">

    {{-- Header Kartu --}}
    <div class="{{ $headerColor }} p-3 sm:p-4 text-center rounded-2xl border">
        <h3 class="text-base font-bold tracking-wide text-white uppercase sm:text-lg">
            {{ $title }}
        </h3>
    </div>

    {{-- Badan Kartu dengan Ikon --}}
    <div class="{{ $bodyColor }} p-6 sm:p-8 flex justify-center items-center relative">
        {{-- Elemen dekoratif di belakang ikon --}}
        <div
            class="absolute text-3xl text-black -translate-x-12 -translate-y-6 opacity-10 sm:-translate-x-16 sm:-translate-y-8 rotate-12">
            <i class="fa-solid fa-ellipsis"></i>
        </div>
        <div
            class="absolute text-3xl text-black translate-x-12 translate-y-6 opacity-10 sm:translate-x-16 sm:translate-y-8 -rotate-12">
            <i class="fa-solid fa-ellipsis"></i>
        </div>

        {{-- Ikon Utama --}}
        <i class="{{ $icon }} text-6xl sm:text-8xl text-gray-800 opacity-75 z-10"></i>
    </div>

    {{-- Footer/Tombol Kartu --}}
    <div class="{{ $linkColor }} p-3 sm:p-4 text-center rounded-2xl border">
        <span class="text-sm font-bold tracking-wide text-white uppercase">
            {{ $linkText }}
        </span>
    </div>
</a>
