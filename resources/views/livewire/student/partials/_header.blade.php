<div class="relative w-full h-10"> {{-- Beri tinggi agar ada ruang untuk header --}}
    <div class="absolute inset-x-0 -top-6 sm:-top-8">
        <div class="flex justify-center">
            <div
                class="inline-flex items-center p-3 space-x-2 bg-yellow-400 border-black rounded-lg shadow-xl border-1 sm:p-4 sm:space-x-3">

                {{-- Ikon --}}
                <div
                    class="flex items-center justify-center flex-shrink-0 w-8 h-8 bg-white border-2 border-black rounded-md sm:w-10 sm:h-10">
                    <i class="text-xl text-black fas fa-book-reader sm:text-2xl"></i>
                </div>

                {{-- Judul --}}
                <h1 class="text-lg font-black text-black uppercase sm:text-xl" style="-webkit-text-stroke: 1px white;">
                    {{ $subject->name }}
                </h1>

            </div>
        </div>
    </div>
</div>
