<div class="min-h-screen px-4 py-10 bg-blue-100"
    style="background-image: url('/images/transparent bg.png'); background-size: cover; background-position: center;">
    <x-ui.student.container title="Referensi Game Edukatif" icon="fa fa-gamepad" header_color="bg-blue-500">

        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Selamat Datang di Portal Game Edukatif!</h2>
            <p class="mt-1 text-gray-600">
                Klik pada gambar-gambar menarik di bawah ini untuk bermain game edukatif yang seru dan menambah
                pengetahuanmu. Semua game ini akan membantumu belajar sambil bersenang-senang!
            </p>
        </div>

        {{-- Grid responsif untuk daftar game --}}
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
            @forelse ($this->games as $game)
                <x-ui.student.game.card :game="$game">
                    {{-- Grid responsif untuk tombol di dalam kartu --}}
                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">

                        <button wire:click="showGameDetail({{ $game->id }})"
                            class="w-full py-2 text-sm font-bold text-gray-800 transition-colors duration-200 bg-white bg-opacity-50 border rounded-lg hover:bg-opacity-100">
                            <i class="mr-1 fas fa-info-circle"></i>
                            Detail
                        </button>

                        <a href="{{ $game->game_url }}" target="_blank"
                            class="flex items-center justify-center w-full py-2 text-sm font-extrabold text-white transition-colors duration-200 bg-black bg-opacity-75 rounded-lg hover:bg-opacity-100">
                            <i class="mr-2 fa-solid fa-gamepad"></i>
                            MAIN
                        </a>
                    </div>
                </x-ui.student.game.card>
            @empty
                <div class="py-12 text-center text-gray-500 col-span-full">
                    <i class="mb-4 text-5xl fa-solid fa-ghost"></i>
                    <p class="font-semibold">Yah, belum ada game yang tersedia untuk kelasmu saat ini.</p>
                </div>
            @endforelse
        </div>

        @if ($this->games->hasPages())
            <div class="mt-8">
                {{ $this->games->links() }}
            </div>
        @endif

    </x-ui.student.container>

    <x-ui.modal id="game-detail-modal">
        @if ($selectedGame)
            @if ($selectedGame->image_path)
                <div class="relative h-48 overflow-hidden rounded-t-lg sm:h-64">
                    <img src="{{ Storage::url($selectedGame->image_path) }}" alt="Banner {{ $selectedGame->title }}"
                        class="object-cover w-full h-full border rounded-lg">

                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>

                    <button wire:click="closeModal"
                        class="absolute p-2 text-white transition-all duration-200 bg-black bg-opacity-50 rounded-full top-4 right-4 hover:bg-opacity-75 focus:outline-none">
                        <i class="text-lg fas fa-times"></i>
                    </button>

                    <div class="absolute bottom-4 left-4 right-16">
                        <h2 class="text-xl font-bold text-white sm:text-2xl drop-shadow-lg">{{ $selectedGame->title }}
                        </h2>
                        <div class="flex flex-wrap items-center gap-2 mt-2">
                            <span
                                class="px-3 py-1 text-xs font-semibold text-white bg-blue-600 rounded-full sm:text-sm">
                                {{ $selectedGame->subject->name ?? 'N/A' }}
                            </span>
                            <span
                                class="px-3 py-1 text-xs font-semibold text-white bg-green-600 rounded-full sm:text-sm">
                                Kelas {{ $selectedGame->class->class ?? 'N/A' }}
                            </span>
                            <span
                                class="px-3 py-1 text-xs font-semibold text-white rounded-full bg-cyan-600 sm:text-sm">
                                {{ $selectedGame->subject->kurikulum ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>
            @endif

            <div class="p-4 sm:p-6">
                @if (!$selectedGame->image_path)
                    <div class="flex items-start justify-between pb-4 border-b">
                        <div>
                            <h2 class="text-xl font-bold text-gray-800 sm:text-2xl">{{ $selectedGame->title }}</h2>
                            <div class="flex flex-wrap items-center gap-2 mt-2">
                                <span
                                    class="px-3 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full sm:text-sm">
                                    {{ $selectedGame->subject->name ?? 'N/A' }}
                                </span>
                                <span
                                    class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full sm:text-sm">
                                    Kelas {{ $selectedGame->class->class ?? 'N/A' }}
                                </span>
                                <span
                                    class="px-3 py-1 text-xs font-semibold rounded-full text-cyan-800 bg-cyan-100 sm:text-sm">
                                    {{ $selectedGame->subject->kurikulum ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                        <button wire:click="closeModal"
                            class="p-2 text-gray-500 rounded-full hover:bg-gray-200 focus:outline-none">
                            <i class="text-lg fas fa-times"></i>
                        </button>
                    </div>
                @endif

                <div class="mt-4">
                    @if ($selectedGame->description)
                        <div class="prose max-w-none">
                            {!! $selectedGame->description !!}
                        </div>
                    @else
                        <p class="text-gray-600">
                            Game edukatif yang menarik untuk mata pelajaran
                            {{ $selectedGame->subject->name ?? 'ini' }}.
                            Klik "Mainkan Sekarang" untuk memulai petualangan belajar yang seru!
                        </p>
                    @endif

                    <div class="p-4 mt-6 border border-blue-200 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl">
                        <div class="flex flex-col items-start gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex flex-wrap items-center gap-2 sm:gap-4">
                                <div class="flex items-center gap-2 text-blue-700">
                                    <i class="fas fa-book"></i>
                                    <span class="font-semibold">Mata Pelajaran:</span>
                                    <span>{{ $selectedGame->subject->name ?? 'N/A' }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-green-700">
                                    <i class="fas fa-graduation-cap"></i>
                                    <span class="font-semibold">Kelas:</span>
                                    <span>{{ $selectedGame->class->class ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 text-purple-700">
                                <i class="fas fa-gamepad"></i>
                                <span class="font-semibold">Game Edukatif</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3 pt-6 mt-6 border-t sm:flex-row sm:justify-between">
                    <button type="button" wire:click="closeModal"
                        class="w-full px-6 py-3 text-gray-700 transition-colors duration-200 bg-gray-100 border border-gray-300 rounded-lg sm:w-auto hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        <i class="mr-2 fas fa-arrow-left"></i>
                        Kembali
                    </button>
                    <a href="{{ $selectedGame->game_url }}" target="_blank"
                        class="flex items-center justify-center w-full px-6 py-3 text-white transition-all duration-200 transform rounded-lg shadow-lg sm:w-auto bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="mr-2 fas fa-play"></i>
                        Mainkan Sekarang
                    </a>
                </div>
            </div>
        @endif
    </x-ui.modal>
</div>
