<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\User;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\Quiz;
use App\Models\Task;

#[Layout('layouts.dashboard')]
#[Title('Admin Dashboard - Metrics')]
class Dashboard extends Component
{
    public $totalStudents;
    public $totalTeachers;
    public $totalClasses;
    public $totalSubjects;
    public $totalQuizzes;
    public $totalTasks;

    public function mount()
    {
        $this->totalStudents = User::role('siswa')->count();
        $this->totalTeachers = User::role('guru')->count();
        $this->totalClasses = Classes::count();
        $this->totalSubjects = Subject::count();
        $this->totalQuizzes = Quiz::count();
        $this->totalTasks = Task::count();
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
