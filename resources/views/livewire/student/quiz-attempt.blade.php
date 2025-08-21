<div class="min-h-screen px-4 py-10 bg-blue-100"
    style="background-image: url('/images/transparent bg.png'); background-size: cover; background-position: center;">
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
        }" x-init="init()" class="py-6 sm:py-10">


            <div class="max-w-4xl p-2 mx-auto shadow-lg bg-white/80 backdrop-blur-sm rounded-2xl">
                {{-- Header Info Kuis --}}
                <div class="flex flex-col items-center justify-between gap-3 px-4 py-3 sm:flex-row">
                    <div
                        class="flex items-center justify-center w-full px-4 py-2 font-bold text-center text-gray-800 bg-gray-200 border-2 border-gray-400 rounded-full sm:w-auto">
                        <i class="mr-2 text-lg fa-solid fa-book"></i>
                        <span class="text-sm sm:text-base">{{ $quiz->subject->name }} - {{ $quiz->title }}</span>
                    </div>

                    {{-- Timer (Floating di mobile, statis di desktop) --}}
                    <div
                        class="fixed z-50 flex items-center px-3 py-1 font-bold text-gray-800 bg-green-200 border-2 border-green-400 rounded-full shadow-lg top-24 right-4 sm:static sm:px-4 sm:py-2">
                        <i class="mr-2 text-lg sm:text-xl fa-solid fa-clock"></i>
                        <span x-text="formatTime()" class="text-xl sm:text-2xl"
                            style="font-family: 'Courier New', Courier, monospace;"></span>
                    </div>
                </div>


                {{-- Konten Soal --}}
                <div class="w-full p-4 bg-white rounded-lg shadow-inner sm:p-6">
                    @if ($currentQuestion)
                        <div class="relative py-6">
                            <div
                                class="absolute top-0 px-3 py-1 mt-4 text-sm font-semibold text-gray-700 bg-gray-100 border-2 border-gray-300 rounded-md left-2 sm:left-4">
                                Soal {{ $currentQuestionIndex + 1 }}/{{ $questions->count() }}
                            </div>
                            <div class="pt-12 text-center">
                                @if ($currentQuestion->image_path)
                                    <div class="my-4">
                                        <img src="{{ asset('storage/' . $currentQuestion->image_path) }}"
                                            alt="Gambar Soal"
                                            class="inline-block w-full max-w-xs rounded-lg shadow-md sm:max-w-sm">
                                    </div>
                                @endif
                                <p class="px-2 text-lg leading-relaxed text-gray-900 sm:text-xl">
                                    {{ $currentQuestion->question_text }}</p>
                            </div>
                            <div class="max-w-lg mx-auto mt-8 space-y-3 sm:space-y-4">
                                @foreach ($this->currentQuestionOptions as $optionKey => $optionText)
                                    <label wire:key="question-{{ $currentQuestionIndex }}-option-{{ $optionKey }}"
                                        class="flex items-center w-full p-3.5 text-left transition-all duration-200 bg-white border-2 border-gray-400 rounded-lg cursor-pointer sm:p-4 hover:bg-gray-100 hover:border-gray-600 active:scale-95"
                                        :class="{ '!bg-blue-200 !border-blue-500 ring-2 ring-blue-300': @js($userAnswers[$currentQuestionIndex] ?? null) === '{{ $optionKey }}' }">
                                        <input type="radio" wire:model.live="userAnswers.{{ $currentQuestionIndex }}"
                                            value="{{ $optionKey }}" class="hidden">
                                        <span
                                            class="mr-3 text-base font-bold sm:mr-4 sm:text-lg">{{ $optionKey }}.</span>
                                        <span class="text-base sm:text-lg">{{ $optionText }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Navigasi Soal --}}
                        <div class="flex justify-between pt-6 mt-4">
                            <button type="button" wire:click="previousQuestion"
                                @if ($currentQuestionIndex == 0) disabled @endif
                                class="px-5 py-2 font-bold text-black transition-transform bg-yellow-300 border-2 border-black rounded-lg sm:px-8 hover:bg-yellow-400 disabled:opacity-50 disabled:cursor-not-allowed active:scale-95">
                                <i class="fas fa-arrow-left"></i>
                                <span class="hidden ml-2 sm:inline">Kembali</span>
                            </button>

                            @if ($currentQuestionIndex < $questions->count() - 1)
                                <button type="button" wire:click="nextQuestion"
                                    class="px-5 py-2 font-bold text-black transition-transform bg-green-300 border-2 border-black rounded-lg sm:px-8 hover:bg-green-400 active:scale-95">
                                    <span class="hidden mr-2 sm:inline">Lanjut</span>
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            @else
                                <button type="button" wire:click="confirmFinish"
                                    class="px-5 py-2 font-bold text-white transition-transform bg-blue-500 border-2 border-black rounded-lg sm:px-8 hover:bg-blue-600 active:scale-95">
                                    <span class="hidden mr-2 sm:inline">Selesai</span>
                                    <i class="fas fa-check"></i>
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
