<?php

namespace App\Livewire\Teacher;

use App\Models\Classes;
use App\Models\Subject;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.teacher')]
#[Title('Penilaian Tugas')]
class ScoreTaskList extends Component
{
    use WithPagination;

    // Properti Filter
    #[Url(as: 'q')]
    public string $search = '';
    #[Url(as: 'mapel')]
    public string $subjectFilter = '';
    #[Url(as: 'kelas')]
    public string $classFilter = '';

    // Lifecycle Hooks
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingSubjectFilter()
    {
        $this->resetPage();
    }
    public function updatingClassFilter()
    {
        $this->resetPage();
    }

    #[Computed]
    public function tasks()
    {
        return Task::with(['subject', 'class', 'submissions'])
            ->where('status', '!=', 'draft')
            ->when($this->search, fn($q) => $q->where('title', 'like', '%' . $this->search . '%'))
            ->when($this->subjectFilter, fn($q) => $q->where('subject_id', $this->subjectFilter))
            ->when($this->classFilter, fn($q) => $q->where('class_id', $this->classFilter))
            ->orderBy('due_date', 'desc')
            ->paginate(10);
    }

    #[Computed]
    public function subjects()
    {
        return Subject::orderBy('name')->get();
    }

    #[Computed]
    public function classes()
    {
        return Classes::orderBy('class')->get();
    }

    public function render()
    {
        return view('livewire.teacher.score-task-list');
    }
}