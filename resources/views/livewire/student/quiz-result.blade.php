<div class="min-h-screen px-4 py-10 bg-blue-100"
    style="background-image: url('/images/transparent bg.png'); background-size: 30rem; background-position: center;">
    <x-ui.student.container>
        <x-slot name="title">Hasil Kuis</x-slot>
        <x-slot name="description">Cek hasil kuis yang telah kamu kerjakan.</x-slot>
        <div class="w-full mx-auto">
            <div class="w-full p-8 text-center bg-white rounded-lg shadow-xl">
                <h1 class="text-2xl font-bold text-gray-800">Cek Hasil Kuismu</h1>
                <p class="mt-2 text-lg font-semibold text-gray-700">{{ $attempt->quiz->title }}</p>

                <div
                    class="w-fit mt-6 mx-auto p-4 @if ($attempt->score >= $attempt->quiz->passing_score) bg-green-50 border-green-200 @else bg-red-300 border-red-400 @endif rounded-lg">
                    <p class="text-white">Skor Akhir</p>
                    <p class="text-5xl font-bold text-white">{{ round($attempt->score) }}/100</p>
                    <p class="text-sm text-white">Dari {{ $attempt->quiz->total_questions }} Soal</p>
                </div>

                {{-- Pesan --}}
                <div class="mt-4">
                    @if ($attempt->score >= $attempt->quiz->passing_score)
                        <p class="text-lg font-semibold text-green-700">Selamat! Kamu lulus kuis ini.</p>
                    @else
                        <p class="text-lg font-semibold text-red-700">Maaf, kamu belum lulus kuis ini.</p>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-4 mt-6 mb-2 text-center">
                    <div class="p-4 bg-green-100 rounded-lg">
                        <p class="text-2xl font-bold text-green-700">{{ $attempt->correct_answers }}</p>
                        <p class="text-sm text-green-600">Jawaban Benar</p>
                    </div>
                    <div class="p-4 bg-red-100 rounded-lg">
                        <p class="text-2xl font-bold text-red-700">{{ $attempt->incorrect_answers }}</p>
                        <p class="text-sm text-red-600">Jawaban Salah</p>
                    </div>
                </div>


                <h2 class="text-xl font-bold text-gray-800">Detail Jawaban</h2>
                <div class="mt-4 space-y-6">
                    @foreach ($attempt->studentAnswers as $index => $answer)
                        <div
                            class="p-4 border rounded-lg @if ($answer->is_correct) border-green-200 bg-green-50 @else border-red-200 bg-red-50 @endif">
                            <p class="font-semibold">{{ $index + 1 }}. {{ $answer->question->question_text }}</p>

                            {{-- PERUBAHAN: Tampilkan gambar jika ada --}}
                            @if ($answer->question->image_path)
                                <div class="my-4 text-center">
                                    <img src="{{ asset('storage/' . $answer->question->image_path) }}" alt="Gambar Soal"
                                        class="inline-block max-w-sm rounded-lg shadow-md">
                                </div>
                            @endif

                            <div class="mt-2 text-sm">
                                @php
                                    $chosenAnswerKey = $answer->chosen_option;
                                    $chosenAnswerText = $this->getAnswerText($answer, $chosenAnswerKey);
                                    $correctAnswerKey = $answer->question->correct_option;
                                    $correctAnswerText = $this->getAnswerText($answer, $correctAnswerKey);
                                @endphp

                                <p>Jawabanmu:
                                    <span class="font-bold">
                                        @if ($chosenAnswerKey)
                                            {{ $chosenAnswerKey }}. {{ $chosenAnswerText }}
                                        @else
                                            Tidak dijawab
                                        @endif
                                    </span>
                                </p>
                                <p>Kunci Jawaban:
                                    <span class="font-bold text-green-700">
                                        {{ $correctAnswerKey }}. {{ $correctAnswerText }}
                                    </span>
                                </p>
                            </div>

                            @if ($answer->question->explanation)
                                <div class="p-3 mt-3 text-sm border-t border-gray-200">
                                    <p class="font-bold">Pembahasan:</p>
                                    <p>{{ $answer->question->explanation }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('student.quizzes') }}" wire:navigate
                    class="px-8 py-3 font-bold text-white bg-blue-500 rounded-lg hover:bg-blue-600">Kembali ke Daftar
                    Kuis</a>
            </div>
        </div>

    </x-ui.student.container>
</div>
