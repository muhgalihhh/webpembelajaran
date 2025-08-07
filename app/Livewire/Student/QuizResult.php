<?php

namespace App\Livewire\Student;

use App\Models\QuizAttempt;
use App\Models\StudentAnswer;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.landing')]
#[Title('Hasil Kuis')]
class QuizResult extends Component
{
    public QuizAttempt $attempt;

    public function mount(QuizAttempt $attempt)
    {
        // Pastikan siswa hanya bisa melihat hasil miliknya
        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }
        $this->attempt = $attempt->load('quiz.subject', 'studentAnswers.question');
    }

    /**
     * Helper method untuk mendapatkan teks lengkap dari sebuah pilihan jawaban.
     *
     * @param StudentAnswer $answer
     * @param string|null $optionKey Kunci pilihan (misal: 'A', 'B').
     * @return string Teks jawaban lengkap.
     */
    public function getAnswerText(StudentAnswer $answer, ?string $optionKey): string
    {
        if (empty($optionKey) || !$answer->question) {
            return '';
        }

        $column = 'option_' . strtolower($optionKey);
        return $answer->question->{$column} ?? 'Opsi tidak ditemukan';
    }


    public function render()
    {
        return view('livewire.student.quiz-result');
    }
}