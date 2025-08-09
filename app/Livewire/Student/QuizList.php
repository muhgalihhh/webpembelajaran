<?php

namespace App\Livewire\Student;

use App\Models\Curriculum;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.landing')]
#[Title('Daftar Kuis')]
class QuizList extends Component
{
    use WithPagination;

    public bool $showStartConfirmation = false;
    public ?Quiz $selectedQuiz = null;

    // Properti BARU untuk filter kurikulum
    #[Url(as: 'kurikulum')]
    public string $kurikulumFilter = '';

    // Lifecycle hook BARU untuk mereset paginasi saat filter berubah
    public function updatingKurikulumFilter()
    {
        $this->resetPage();
    }

    #[Computed]
    public function quizzes()
    {
        $studentClassId = Auth::user()->class_id;
        $studentId = Auth::id();

        return Quiz::with([
            'subject',
            'questions',
            'attempts' => fn($query) => $query->where('user_id', $studentId)
        ])
            ->where('class_id', $studentClassId)
            // Query BARU untuk memfilter berdasarkan kurikulum melalui relasi subject
            ->whereHas('subject', function ($query) {
                $query->when($this->kurikulumFilter, function ($q) {
                    $q->where('kurikulum', $this->kurikulumFilter);
                });
            })
            ->latest()
            ->paginate(8);
    }

    // Computed property BARU untuk mendapatkan opsi kurikulum
    #[Computed]
    public function kurikulumOptions()
    {
        return Curriculum::where('is_active', true)->pluck('name', 'name')->all();
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