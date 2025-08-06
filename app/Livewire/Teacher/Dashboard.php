<?php

namespace App\Livewire\Teacher;

use App\Models\Classes;
use App\Models\QuizAttempt;
use App\Models\Subject;
use App\Models\TaskSubmission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
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

    // Filter Utama
    #[Url(as: 'aktivitas', history: true)]
    public $activityType = 'quiz'; // 'quiz' atau 'tugas'

    // Filter lainnya
    #[Url(as: 'kelas', history: true)]
    public $classFilter = '';
    #[Url(as: 'mapel', history: true)]
    public $subjectFilter = '';
    #[Url(as: 'q', history: true)]
    public $searchQuery = '';
    #[Url(history: true)]
    public $sortBy = 'created_at';
    #[Url(history: true)]
    public $sortDirection = 'desc';

    public function setActivityType($type)
    {
        $this->activityType = $type;
        $this->resetPage();
    }

    public function updating($property)
    {
        if (in_array($property, ['classFilter', 'subjectFilter', 'searchQuery'])) {
            $this->resetPage();
        }
    }

    #[Computed]
    public function results()
    {
        if ($this->activityType === 'quiz') {
            $query = QuizAttempt::query()->with(['user.class', 'quiz.subject']);
            $query->when($this->searchQuery, fn($q) => $q->whereHas('quiz', fn($sq) => $sq->where('title', 'like', '%' . $this->searchQuery . '%')));
            $query->when($this->classFilter, fn($q) => $q->whereHas('user', fn($sq) => $sq->where('class_id', $this->classFilter)));
            $query->when($this->subjectFilter, fn($q) => $q->whereHas('quiz', fn($sq) => $sq->where('subject_id', $this->subjectFilter)));
        } else {
            $query = TaskSubmission::query()->with(['student.class', 'task.subject']);
            $query->when($this->searchQuery, fn($q) => $q->whereHas('task', fn($sq) => $sq->where('title', 'like', '%' . $this->searchQuery . '%')));
            $query->when($this->classFilter, fn($q) => $q->whereHas('student', fn($sq) => $sq->where('class_id', $this->classFilter)));
            $query->when($this->subjectFilter, fn($q) => $q->whereHas('task', fn($sq) => $sq->where('subject_id', $this->subjectFilter)));
        }

        $query->orderBy($this->sortBy, $this->sortDirection);
        return $query->paginate(10);
    }

    #[Computed]
    public function filterOptions()
    {
        return [
            'subjects' => Subject::orderBy('name')->get(),
            'classes' => Classes::orderBy('class')->get(),
        ];
    }

    /**
     * ==========================================================
     * ==== BAGIAN STATISTIK YANG SUDAH DIBUAT DINAMIS ====
     * ==========================================================
     */
    #[Computed]
    public function stats()
    {
        if ($this->activityType === 'quiz') {
            $query = QuizAttempt::query();
            // Terapkan filter yang relevan untuk kuis
            $query->when($this->classFilter, fn($q) => $q->whereHas('user', fn($sq) => $sq->where('class_id', $this->classFilter)));
            $query->when($this->subjectFilter, fn($q) => $q->whereHas('quiz', fn($sq) => $sq->where('subject_id', $this->subjectFilter)));
        } else {
            $query = TaskSubmission::query();
            // Terapkan filter yang relevan untuk tugas
            $query->when($this->classFilter, fn($q) => $q->whereHas('student', fn($sq) => $sq->where('class_id', $this->classFilter)));
            $query->when($this->subjectFilter, fn($q) => $q->whereHas('task', fn($sq) => $sq->where('subject_id', $this->subjectFilter)));
        }

        $totalAttempts = $query->count();
        $activeStudents = $query->distinct('user_id')->count();
        $averageScore = $totalAttempts > 0 ? round($query->avg('score'), 1) : 0;

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
        return view('livewire.teacher.dashboard');
    }

    public function deleteAttempt($id)
    {
        try {
            if ($this->activityType === 'quiz') {
                QuizAttempt::findOrFail($id)->delete();
            } else {
                TaskSubmission::findOrFail($id)->delete();
            }
            // Kirim notifikasi sukses (jika Anda menggunakan sistem notifikasi)
            // session()->flash('message', 'Data pengerjaan berhasil dihapus.');
        } catch (\Exception $e) {
            // Kirim notifikasi error
            // session()->flash('error', 'Gagal menghapus data.');
        }
    }
}
