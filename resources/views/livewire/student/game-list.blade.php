<div class="min-h-screen px-4 py-10 bg-blue-100"
    style="background-image: url('/images/transparent bg.png'); background-size: cover; background-position: center;">
    <x-ui.student.container title="Referensi Game Edukatif" icon="fa fa-gamepad" header_color="bg-blue-500">


        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <p class="text-lg font-semibold text-blue-800">Belajar sambil bermain lebih menyenangkan!</p>
        </div>

        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Selamat Datang di Portal Game Edukatif!</h2>
            <p class="mt-1 text-gray-600">
                Klik pada gambar-gambar menarik di bawah ini untuk bermain game edukatif yang seru dan menambah
                pengetahuanmu. Semua game ini akan membantumu belajar sambil bersenang-senang!
            </p>
        </div>

        <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
            @forelse ($this->games as $game)
                <x-ui.student.game.card :game="$game" />
            @empty
                <div class="py-12 text-center text-gray-500 col-span-full">
                    <i class="mb-4 text-5xl fa-solid fa-ghost"></i>
                    <p class="font-semibold">Yah, belum ada game yang tersedia untuk kelasmu saat ini.</p>
                </div>
            @endforelse
        </div>

        @if ($this->games->count() > 0)
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-500">Total Game Tersedia: {{ $this->games->count() }}</p>
            </div>
        @endif
        @if ($this->games->hasPages())
            <div class="mt-8">
                {{ $this->games->links() }}
            </div>
        @endif


    </x-ui.student.container>
</div>
