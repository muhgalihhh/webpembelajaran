<?php

namespace App\Livewire\Student;

use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.landing')]
#[Title('Daftar Kuis')]
class QuizList extends Component
{
    use WithPagination;

    public bool $showStartConfirmation = false;
    public ?Quiz $selectedQuiz = null;

    #[Computed]
    public function quizzes()
    {
        $studentClassId = Auth::user()->class_id;
        $studentId = Auth::id();

        // Ambil kuis dan juga data percobaan (attempt) oleh siswa yang sedang login
        return Quiz::with([
            'subject',
            'questions',
            'attempts' => fn($query) => $query->where('user_id', $studentId)
        ])
            ->where('class_id', $studentClassId)
            ->latest()
            ->paginate(8);
    }

    #[On('confirm-start-quiz')]
    public function confirmStartQuiz(int $quizId)
    {
        $this->selectedQuiz = Quiz::find($quizId);
        if ($this->selectedQuiz) {
            $this->showStartConfirmation = true;
        }
    }

    public function startQuiz()
    {
        if ($this->selectedQuiz) {
            $this->showStartConfirmation = false;
            return $this->redirect(route('student.quizzes.attempt', $this->selectedQuiz->id), navigate: true);
        }
    }

    public function cancelStart()
    {
        $this->showStartConfirmation = false;
        $this->selectedQuiz = null;
    }

    public function render()
    {
        return view('livewire.student.quiz-list');
    }
}
