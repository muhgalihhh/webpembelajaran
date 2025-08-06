<div class="min-h-screen px-4 py-10 bg-blue-100"
    style="background-image: url('/images/transparent bg.png'); background-size: 30rem; background-position: center;"
    x-data="{ isLoaded: false }" x-init="setTimeout(() => { isLoaded = true }, 50)">
    <x-ui.student.container title="Halaman Kuis" header_color="bg-gradient-to-r from-green-200 to-green-300">
        @if (!$quizStarted)
            <div class="flex items-center justify-center py-10">
                <div class="w-full max-w-lg p-8 text-center bg-white border rounded-lg shadow-xl">
                    <i class="mb-4 text-6xl text-blue-500 fa-solid fa-circle-question"></i>
                    <h1 class="text-2xl font-bold text-gray-800">SIAP MENGIKUTI KUIS INI?</h1>
                    <p class="mt-2 text-lg font-semibold text-gray-700">
                        Kamu akan mengerjakan kuis <span class="text-blue-600">{{ $quiz->title }}</span>
                    </p>

                    <div class="p-4 mt-4 text-left border-l-4 border-blue-500 rounded-r-lg bg-blue-50">
                        <p><strong>Mata Pelajaran:</strong> {{ $quiz->subject->name }}</p>
                        <p><strong>Jumlah Soal:</strong> {{ $questions->count() }}</p>
                        <p><strong>Durasi:</strong> {{ $quiz->duration_minutes }} Menit</p>
                    </div>

                    <p class="mt-4 text-sm text-gray-500">
                        Pastikan kamu sudah siap, duduk dengan tenang, dan fokus. Baca soal dengan teliti dan jawab
                        dengan
                        tepat.
                    </p>

                    <div class="flex justify-center mt-6 space-x-4">
                        <a href="{{ route('student.quizzes') }}" wire:navigate
                            class="px-8 py-3 font-bold text-white bg-red-500 rounded-lg hover:bg-red-600">
                            Tidak
                        </a>
                        <button wire:click="startQuiz"
                            class="px-8 py-3 font-bold text-white bg-green-500 rounded-lg hover:bg-green-600">
                            Ya, Mulai
                        </button>
                    </div>
                </div>
            </div>
        @else
            <div x-data="{
                time: @entangle('timeRemaining').live,
                timer: null,
                init() {
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
                    const minutes = Math.floor(this.time / 60).toString().padStart(2, '0');
                    const seconds = (this.time % 60).toString().padStart(2, '0');
                    return `${minutes}:${seconds}`;
                }
            }" class="py-10">

                {{-- Kontainer Luar Utama --}}
                <div class="max-w-4xl p-2 mx-auto bg-white shadow-lg rounded-2xl">
                    {{-- Header (Judul Halaman & Timer) --}}
                    <div class="flex items-start justify-between px-4 py-2">
                        <div
                            class="flex items-center px-4 py-1 font-bold text-black bg-green-200 border-2 border-black rounded-full">
                            <i class="mr-2 text-xl text-black fa-solid fa-clock"></i>
                            <span x-text="formatTime()" class="text-2xl"
                                style="font-family: 'Courier New', Courier, monospace;"></span>
                        </div>
                    </div>

                    {{-- Kotak Soal Utama --}}
                    <div class="w-full p-4 bg-white rounded-lg shadow-inner sm:p-6">
                        <h2 class="pb-4 text-2xl font-bold text-center text-gray-800 ">
                            {{ Str::upper($quiz->title) }}</h2>

                        @if ($currentQuestion)
                            <div class="relative py-6">
                                <div
                                    class="absolute top-0 px-3 py-1 mt-4 font-semibold text-gray-700 bg-gray-100 border-2 border-gray-300 rounded-md left-4">
                                    Soal {{ $currentQuestionIndex + 1 }}/{{ $questions->count() }}
                                </div>

                                <div class="pt-12 text-center">
                                    <p class="text-xl text-gray-900">{{ $currentQuestion->question_text }}</p>
                                </div>

                                <div class="max-w-lg mx-auto mt-8 space-y-4">
                                    @foreach (['A', 'B', 'C', 'D', 'E'] as $option)
                                        @php $optionValue = 'option_' . strtolower($option); @endphp
                                        @if ($currentQuestion->$optionValue)
                                            <label
                                                class="flex items-center w-full p-3.5 text-left transition-all duration-200 bg-white border-2 border-gray-400 rounded-lg cursor-pointer sm:p-4 hover:bg-gray-100 hover:border-gray-600"
                                                :class="{
                                                    '!bg-blue-200 !border-blue-500 ring-2 ring-blue-300': @js($userAnswers[$currentQuestionIndex] ?? null) === '{{ $option }}'
                                                }">
                                                <input type="radio"
                                                    wire:model.live="userAnswers.{{ $currentQuestionIndex }}"
                                                    value="{{ $option }}" class="hidden">
                                                <span class="mr-4 font-bold">{{ $option }}.</span>
                                                <span
                                                    class="text-base sm:text-lg">{{ $currentQuestion->$optionValue }}</span>
                                            </label>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <div class="flex justify-end pt-6 mt-4">
                                <button wire:click="nextQuestion"
                                    class="px-8 py-2 font-bold text-black bg-green-300 border-2 border-black rounded-lg hover:bg-green-400">
                                    {{ $currentQuestionIndex < $questions->count() - 1 ? 'Selanjutnya' : 'Selesai' }}
                                </button>
                            </div>
                        @else
                            <p class="py-10 text-center text-gray-600">Tidak ada soal dalam kuis ini.</p>
                        @endif
                    </div>
                </div>
            </div>
        @endif
        </x-quiz.container>
</div>
