<?php

namespace App\Livewire\Student;

use App\Models\Quiz;
use App\Models\QuizAttempt as Attempt;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.landing')]
#[Title('Mulai Kuis')]
class QuizAttempt extends Component
{
    public Quiz $quiz;
    public $questions;
    public int $currentQuestionIndex = 0;
    public array $userAnswers = [];
    public ?Attempt $attempt = null;
    public ?int $timeRemaining = null;
    public bool $quizStarted = false;

    public function mount(Quiz $quiz)
    {
        $this->quiz = $quiz;
        $this->questions = $quiz->questions()->inRandomOrder()->get();
        $this->timeRemaining = $quiz->duration_minutes * 60;
    }

    public function startQuiz()
    {
        $this->attempt = Attempt::create([
            'user_id' => Auth::id(),
            'quiz_id' => $this->quiz->id,
            'start_time' => now(),
        ]);

        $this->quizStarted = true;
        $this->dispatch('quiz-started', time: $this->timeRemaining);
    }

    public function selectAnswer($option)
    {
        $this->userAnswers[$this->currentQuestionIndex] = $option;
    }

    public function nextQuestion()
    {
        if ($this->currentQuestionIndex < $this->questions->count() - 1) {
            $this->currentQuestionIndex++;
        } else {
            $this->submitQuiz();
        }
    }

    public function submitQuiz()
    {
        if (!$this->attempt || $this->attempt->is_completed) {
            return;
        }

        $correctAnswers = 0;
        foreach ($this->questions as $index => $question) {
            if (isset($this->userAnswers[$index]) && $this->userAnswers[$index] === $question->correct_option) {
                $correctAnswers++;
            }
        }

        $totalQuestions = $this->questions->count();
        $score = ($totalQuestions > 0) ? ($correctAnswers / $totalQuestions) * 100 : 0;

        $this->attempt->update([
            'score' => $score,
            'correct_answers' => $correctAnswers,
            'incorrect_answers' => $totalQuestions - $correctAnswers,
            'end_time' => now(),
            'is_completed' => true,
        ]);

        // Redirect ke halaman hasil (bisa dibuat nanti)
        // Untuk sekarang, kita redirect ke daftar kuis
        return $this->redirect(route('student.quizzes'), navigate: true);
    }


    public function render()
    {
        return view('livewire.student.quiz-attempt', [
            'currentQuestion' => $this->questions[$this->currentQuestionIndex] ?? null
        ]);
    }
}
