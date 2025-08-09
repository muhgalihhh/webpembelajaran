@props(['quiz'])

@php
    // Cek apakah kuis siap untuk dikerjakan (published dan punya soal)
    $isReady = $quiz->status === 'publish' && $quiz->questions->count() > 0;

    // Ambil data percobaan (attempt) siswa untuk kuis ini
    $attempt = $quiz->attempts->first();

    // Tentukan status kelulusan jika kuis sudah selesai
    $hasPassed = false;
    if ($attempt && $attempt->is_completed) {
        $hasPassed = $attempt->score >= $quiz->passing_score;
    }

    // --- PALET WARNA BERDASARKAN MATA PELAJARAN ---
    $subjectName = strtolower($quiz->subject->name ?? 'default');
    $palettes = [
        'matematika' => [
            'container' => 'bg-blue-400',
            'content' => 'bg-blue-500',
            'button' => 'bg-blue-600 hover:bg-blue-700',
        ],
        'bahasa indonesia' => [
            'container' => 'bg-red-400',
            'content' => 'bg-red-500',
            'button' => 'bg-red-600 hover:bg-red-700',
        ],
        'ipa' => [
            'container' => 'bg-green-400',
            'content' => 'bg-green-500',
            'button' => 'bg-green-600 hover:bg-green-700',
        ],
        'ilmu pengetahuan alam' => [
            'container' => 'bg-green-400',
            'content' => 'bg-green-500',
            'button' => 'bg-green-600 hover:bg-green-700',
        ],
        'ips' => [
            'container' => 'bg-yellow-400',
            'content' => 'bg-yellow-500',
            'button' => 'bg-yellow-600 hover:bg-yellow-700',
        ],
        'ilmu pengetahuan sosial' => [
            'container' => 'bg-yellow-400',
            'content' => 'bg-yellow-500',
            'button' => 'bg-yellow-600 hover:bg-yellow-700',
        ],
        'ppkn' => [
            'container' => 'bg-indigo-400',
            'content' => 'bg-indigo-500',
            'button' => 'bg-indigo-600 hover:bg-indigo-700',
        ],
        'default' => [
            'container' => 'bg-gray-400',
            'content' => 'bg-gray-500',
            'button' => 'bg-gray-600 hover:bg-gray-700',
        ],
    ];
    $colorPalette = $palettes[$subjectName] ?? $palettes['default'];
@endphp

<div
    class="quiz-card p-4 overflow-hidden transition-all duration-300 border rounded-2xl hover:-translate-y-2 {{ $isReady ? $colorPalette['container'] : 'bg-gray-300' }} shadow-[6px_6px_0px_rgba(0,0,0,0.2)]">
    <div class="{{ $isReady ? $colorPalette['content'] : 'bg-gray-400' }} rounded-xl overflow-hidden border text-black">
        <div class="relative p-4">
            {{-- PERUBAHAN: Tampilkan status Lulus/Tidak Lulus jika sudah selesai --}}
            @if ($attempt && $attempt->is_completed)
                <div class="absolute top-0 right-0 p-3">
                    <span
                        class="px-3 py-1 text-xs font-bold text-white {{ $hasPassed ? 'bg-green-500' : 'bg-red-500' }} rounded-full shadow-lg">
                        {{ $hasPassed ? 'LULUS' : 'TIDAK LULUS' }}
                    </span>
                </div>
            @endif

            {{-- Konten Kartu --}}
            <div class="flex items-center justify-between mb-2">
                <div class="px-3 py-1 bg-white border rounded-lg bg-opacity-20">
                    <h3 class="text-lg font-bold tracking-wide uppercase">{{ $quiz->subject->name }}</h3>
                    <p class="text-sm text-gray-700">{{ $quiz->title }}</p>
                    {{-- kurikulum --}}
                    <p class="text-xs text-gray-600">Kurikulum: {{ $quiz->subject->kurikulum }}</p>
                </div>
            </div>

            @if ($attempt && $attempt->is_completed)
                <div class="my-2">
                    <p class="text-sm opacity-90">Nilai Kamu:</p>
                    <p class="text-3xl font-bold">{{ round($attempt->score) }}</p>
                </div>
            @else
                <div class="mx-2 mb-2 space-y-1 text-sm opacity-90">
                    <p>Terdapat <strong>{{ $quiz->questions->count() }} Soal</strong> Dari Guru</p>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-clock"></i>
                        <span><strong>DURASI : {{ $quiz->duration_minutes }} MENIT</strong></span>
                    </div>
                </div>
            @endif

            <div class="p-3 mx-2 mb-2 bg-white rounded-lg bg-opacity-20">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold">QUIZ</span>
                    <div class="flex space-x-1">
                        @for ($i = 1; $i <= 5; $i++)
                            <div class="w-8 h-2 bg-black bg-opacity-50 rounded-full"></div>
                        @endfor
                    </div>
                </div>
            </div>
            <p class="text-md leading-relaxed opacity-80 min-h-[50px] mx-2">
                {{ $quiz->description ?: 'Pastikan kamu sudah siap, duduk dengan tenang, dan fokus. Baca soal dengan teliti dan jawab dengan tepat.' }}
            </p>
        </div>

        {{-- Tombol Aksi Dinamis --}}
        <div>
            @if (!$isReady)
                <button disabled
                    class="block w-full px-6 py-3 font-bold text-center text-white bg-gray-500 shadow-lg cursor-not-allowed rounded-b-xl">
                    <div class="flex items-center justify-center space-x-2"><span>Kuis Belum Tersedia</span><i
                            class="text-sm fas fa-lock"></i></div>
                </button>
            @elseif ($attempt && $attempt->is_completed)
                <a href="{{ route('student.quizzes.result', $attempt->id) }}" wire:navigate
                    class="block w-full px-6 py-3 font-bold text-center text-white bg-green-600 shadow-lg hover:bg-green-700 rounded-b-xl">
                    <div class="flex items-center justify-center space-x-2"><span>Lihat Hasil</span><i
                            class="text-sm fas fa-eye"></i></div>
                </a>
            @elseif ($attempt && !$attempt->is_completed)
                <a href="{{ route('student.quizzes.attempt', $quiz->id) }}" wire:navigate
                    class="block w-full px-6 py-3 font-bold text-center text-white bg-yellow-500 shadow-lg hover:bg-yellow-600 rounded-b-xl">
                    <div class="flex items-center justify-center space-x-2"><span>Lanjutkan Mengerjakan</span><i
                            class="text-sm fas fa-arrow-right"></i></div>
                </a>
            @else
                <button wire:click="$dispatch('confirm-start-quiz', { quizId: {{ $quiz->id }} })"
                    class="block w-full py-3 px-6 text-center font-bold text-white {{ $colorPalette['button'] }} rounded-b-xl shadow-lg">
                    <div class="flex items-center justify-center space-x-2"><span>Mulai Kuis</span><i
                            class="text-sm fas fa-play"></i></div>
                </button>
            @endif
        </div>
    </div>
</div>

<style>
    .quiz-card:hover {
        transform: translateY(-8px);
    }

    .quiz-card {
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
</style>
