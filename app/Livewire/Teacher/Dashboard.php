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
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.teacher')]
#[Title('Dashboard Guru')]
class Dashboard extends Component
{
    use WithPagination;

    // Properti untuk filter dengan sinkronisasi URL
    #[Url(as: 'kelas', history: true)]
    public $classFilter = '';

    #[Url(as: 'mapel', history: true)]
    public $subjectFilter = '';

    #[Url(as: 'q', history: true)]
    public $quizSearch = ''; // Disesuaikan dengan nama di view

    #[Url(history: true)]
    public $sortBy = 'created_at';

    #[Url(history: true)]
    public $sortDirection = 'desc';

    // Lifecycle hooks untuk mereset paginasi saat filter berubah
    public function updatingClassFilter()
    {
        $this->resetPage();
    }
    public function updatingSubjectFilter()
    {
        $this->resetPage();
    }
    public function updatingQuizSearch()
    {
        $this->resetPage();
    }

    /**
     * Mengambil data hasil kuis (quiz attempts) dengan filter.
     */
    #[Computed]
    public function attempts()
    {
        return QuizAttempt::with(['student.class', 'quiz.subject'])
            ->when($this->subjectFilter, function ($query) {
                $query->whereHas('quiz', fn($q) => $q->where('subject_id', $this->subjectFilter));
            })
            ->when($this->quizSearch, function ($query) {
                $query->whereHas('quiz', fn($q) => $q->where('title', 'like', '%' . $this->quizSearch . '%'));
            })
            ->when($this->classFilter, function ($query) {
                $query->whereHas('student', fn($q) => $q->where('class_id', $this->classFilter));
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);
    }

    /**
     * Mengambil data untuk opsi filter di dropdown.
     */
    #[Computed]
    public function filterOptions()
    {
        return [
            'subjects' => Subject::orderBy('name')->get(),
            'classes' => Classes::orderBy('class')->get(),
        ];
    }

    /**
     * Menghitung statistik dasar.
     */
    #[Computed]
    public function stats()
    {
        // Sebaiknya query ini juga difilter berdasarkan guru yang login jika ada relasinya
        $allAttempts = QuizAttempt::query();

        $totalAttempts = $allAttempts->count();
        $activeStudents = $allAttempts->distinct('user_id')->count();
        $averageScore = $totalAttempts > 0 ? round($allAttempts->avg('score'), 1) : 0;

        return [
            'activeStudents' => $activeStudents,
            'totalAttempts' => $totalAttempts,
            'averageScore' => $averageScore,
        ];
    }

    /**
     * Mengatur sorting tabel.
     */
    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortBy = $field;
    }

    /**
     * Merender view.
     * Tidak perlu mengirim data secara manual, view akan mengakses computed property.
     */
    public function render()
    {
        return view('livewire.teacher.dashboard');
    }
}