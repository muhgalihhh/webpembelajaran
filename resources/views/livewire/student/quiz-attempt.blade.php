<div class="min-h-screen px-4 py-10 bg-blue-100"
    style="background-image: url('/images/transparent bg.png'); background-size: 30rem; background-position: center;">
    <x-ui.student.container :title="$quiz->title" header_color="bg-gradient-to-r from-blue-200 to-green-300">

        <div x-data="{
            time: @entangle('timeRemaining').live,
            timer: null,
            init() {
                if (this.timer) clearInterval(this.timer);
                this.timer = setInterval(() => {
                    if (this.time > 0) {
                        this.time--;
                    } else {
                        clearInterval(this.timer);
                        $wire.submitQuiz();
                    }
                }, 1000);
            },
            formatTime() {
                if (this.time === null) return '...';
                const minutes = Math.floor(this.time / 60).toString().padStart(2, '0');
                const seconds = (this.time % 60).toString().padStart(2, '0');
                return `${minutes}:${seconds}`;
            }
        }" x-init="init()" class="py-10">
            <div class="max-w-4xl p-2 mx-auto bg-white shadow-lg rounded-2xl">
                <div class="flex items-start justify-between px-4 py-2">
                    <div
                        class="flex items-center px-4 py-1 font-bold text-black bg-gray-200 border-2 border-black rounded-full">
                        <i class="mr-2 text-xl text-black fa-solid fa-book"></i>
                        <span class="text-xl">{{ $quiz->subject->name }} - {{ $quiz->title }}</span>
                    </div>
                    <div
                        class="flex items-center px-4 py-1 font-bold text-black bg-green-200 border-2 border-black rounded-full">
                        <i class="mr-2 text-xl text-black fa-solid fa-clock"></i>
                        <span x-text="formatTime()" class="text-2xl"
                            style="font-family: 'Courier New', Courier, monospace;"></span>
                    </div>
                </div>

                <div class="w-full p-4 bg-white rounded-lg shadow-inner sm:p-6">
                    @if ($currentQuestion)
                        <div class="relative py-6">
                            <div
                                class="absolute top-0 px-3 py-1 mt-4 font-semibold text-gray-700 bg-gray-100 border-2 border-gray-300 rounded-md left-4">
                                Soal {{ $currentQuestionIndex + 1 }}/{{ $questions->count() }}
                            </div>
                            <div class="pt-12 text-center">
                                @if ($currentQuestion->image_path)
                                    <div class="my-4">
                                        <img src="{{ asset('storage/' . $currentQuestion->image_path) }}"
                                            alt="Gambar Soal" class="inline-block max-w-sm rounded-lg shadow-md">
                                    </div>
                                @endif
                                <p class="text-xl text-gray-900">{{ $currentQuestion->question_text }}</p>


                            </div>
                            <div class="max-w-lg mx-auto mt-8 space-y-4">
                                @foreach ($this->currentQuestionOptions as $optionKey => $optionText)
                                    <label
                                        class="flex items-center w-full p-3.5 text-left transition-all duration-200 bg-white border-2 border-gray-400 rounded-lg cursor-pointer sm:p-4 hover:bg-gray-100 hover:border-gray-600"
                                        :class="{ '!bg-blue-200 !border-blue-500 ring-2 ring-blue-300': @js($userAnswers[$currentQuestionIndex] ?? null) === '{{ $optionKey }}' }">
                                        <input type="radio" wire:model="userAnswers.{{ $currentQuestionIndex }}"
                                            value="{{ $optionKey }}" class="hidden">
                                        <span class="mr-4 font-bold">{{ $optionKey }}.</span>
                                        <span class="text-base sm:text-lg">{{ $optionText }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="flex justify-between pt-6 mt-4">
                            <button type="button" wire:click="previousQuestion"
                                @if ($currentQuestionIndex == 0) disabled @endif
                                class="px-8 py-2 font-bold text-black bg-yellow-300 border-2 border-black rounded-lg hover:bg-yellow-400 disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </button>
                            @if ($currentQuestionIndex < $questions->count() - 1)
                                <button type="button" wire:click="nextQuestion"
                                    class="px-8 py-2 font-bold text-black bg-green-300 border-2 border-black rounded-lg hover:bg-green-400">
                                    Selanjutnya <i class="fas fa-arrow-right"></i>
                                </button>
                            @else
                                <button type="button" wire:click="confirmFinish"
                                    class="px-8 py-2 font-bold text-white bg-blue-500 border-2 border-black rounded-lg hover:bg-blue-600">
                                    Selesai <i class="fas fa-check"></i>
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <x-ui.student.quiz.confirm-modal wire:model="showFinishConfirmation" icon="fa-solid fa-flag-checkered"
            iconColor="text-green-500" title="Selesaikan Kuis?"
            message="Yakin jawabanmu sudah benar semua? Kamu tidak akan bisa mengubahnya lagi." confirmText="Ya, Yakin!"
            cancelText="Belum Yakin" wireConfirmAction="submitQuiz" wireCancelAction="cancelFinish" />
    </x-ui.student.container>
</div>
