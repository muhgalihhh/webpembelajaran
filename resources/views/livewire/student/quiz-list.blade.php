<div class="min-h-screen px-4 py-10 bg-blue-100"
    style="background-image: url('/images/transparent bg.png'); background-size: 30rem; background-position: center;"
    x-data="{ isLoaded: false }" x-init="setTimeout(() => { isLoaded = true }, 50)">
    <x-ui.student.container title="Halaman Kuis" header_color="bg-gradient-to-r from-green-200 to-green-300"
        icon="fa-solid fa-question-circle">
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($this->quizzes as $quiz)
                <x-ui.student.quiz.card :quiz="$quiz" />
            @empty
                {{-- Tampilan Jika Tidak Ada Kuis --}}
                <div class="p-12 text-center bg-white rounded-lg shadow-md xl:col-span-3">
                    <div class="flex flex-col items-center text-gray-500">
                        <i class="mb-4 text-5xl fa-solid fa-box-open"></i>
                        <h3 class="text-xl font-semibold">Yah, Belum Ada Kuis</h3>
                        <p class="max-w-md mt-1">Saat ini belum ada kuis yang tersedia untuk kelasmu. Cek lagi nanti,
                            ya!</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if ($this->quizzes->hasPages())
            <div class="px-4 py-6 mt-8 bg-white rounded-lg shadow-md">
                {{ $this->quizzes->links() }}
            </div>
        @endif

        @if ($selectedQuiz)
            <x-ui.student.quiz.confirm-modal wire:model="showStartConfirmation" icon="fa-solid fa-play-circle"
                iconColor="text-blue-500" title="Mulai Kuis?" :message="'Anda akan memulai kuis ' . $selectedQuiz->title . '. Pastikan Anda sudah siap!'" confirmText="Ya, Mulai!"
                cancelText="Batal" wireConfirmAction="startQuiz" wireCancelAction="cancelStart" />
        @endif
    </x-ui.student.container>
</div>
