<?php

namespace App\Livewire\Teacher;

use App\Models\Classes;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.teacher')]
#[Title('Dashboard Guru')]
class Dashboard extends Component
{
    use WithPagination;

    // Properti untuk filter
    public $classFilter = '';
    public $subjectFilter = '';
    public $quizFilter = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    /**
     * Mengambil data hasil kuis (quiz attempts) untuk semua siswa.
     */
    #[Computed]
    public function quizAttempts()
    {
        return QuizAttempt::with(['student.class', 'quiz.subject'])
            // Terapkan filter berdasarkan input dari guru
            ->when($this->subjectFilter, function ($query) {
                $query->whereHas('quiz', function ($subQuery) {
                    $subQuery->where('subject_id', $this->subjectFilter);
                });
            })
            ->when($this->quizFilter, function ($query) {
                $query->where('quiz_id', $this->quizFilter);
            })
            ->when($this->classFilter, function ($query) {
                $query->whereHas('student', function ($subQuery) {
                    $subQuery->where('class_id', $this->classFilter);
                });
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);
    }
    #[Computed]
    public function filterOptions()
    {
        return [
            'subjects' => Subject::orderBy('name')->get(),
            'quizzes' => Quiz::orderBy('title')->get(),
            'classes' => Classes::orderBy('name')->get(),
        ];
    }


    #[Computed]
    public function stats()
    {
        $allAttempts = QuizAttempt::query();

        $totalAttempts = (clone $allAttempts)->count();
        // PERBAIKAN: Mengganti 'student_id' menjadi 'user_id' yang benar.
        $activeStudents = (clone $allAttempts)->distinct('user_id')->count();
        $averageScore = $totalAttempts > 0 ? round((clone $allAttempts)->avg('score'), 1) : 0;

        return [
            'activeStudents' => $activeStudents,
            'totalAttempts' => $totalAttempts,
            'averageScore' => $averageScore,
        ];
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortBy = $field;
    }

    public function render()
    {
        return view('livewire.teacher.dashboard', [
            'attempts' => $this->quizAttempts,
            'stats' => $this->stats,
            'filters' => $this->filterOptions,
        ]);
    }
}
