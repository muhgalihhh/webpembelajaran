@props([
    'title' => 'IPA',
    'link' => '#',
    'bgColor' => 'bg-yellow-300',
    'borderColor' => 'black',
    'footerBg' => 'bg-teal-700',
    'buttonText' => 'Lihat Materi',
])

<a href="{{ $link }}" wire:navigate
    class="block w-full max-w-xs mx-auto bg-teal-700 border sm:max-w-sm rounded-2xl hover:shadow-xl hover:-translate-y-1">
    {{-- Outer container with teal border --}}
    <div class="pt-4 transition-all duration-200 bg-teal-700 border shadow-lg rounded-2xl hover:shadow-xl">

        {{-- Yellow top section - positioned lower to show teal top --}}
        <div class="{{ $bgColor }} rounded-xl h-32 sm:h-36 flex items-center justify-center border">
            <h2 class="text-xl font-black text-white sm:text-4xl" style="-webkit-text-stroke: 1px black;">
                {{ $title }}
            </h2>
        </div>

        {{-- Bottom section with teal background and white button --}}
        <div class="{{ $footerBg }} rounded-b-xl py-3 flex items-center justify-center">
            <div class="px-4 py-1 bg-white border rounded-full">
                <span class="text-xs font-bold text-black sm:text-sm">
                    {{ $buttonText }}
                </span>
            </div>
        </div>

    </div>
</a>
