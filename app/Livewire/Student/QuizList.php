<?php

namespace App\Livewire\Student;

use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.landing')]
#[Title('Daftar Kuis')]
class QuizList extends Component
{
    use WithPagination;

    #[Computed]
    public function quizzes()
    {
        $studentClassId = Auth::user()->class_id;

        // Ambil kuis yang dipublish untuk kelas siswa
        return Quiz::with('subject', 'questions')
            ->where('class_id', $studentClassId)
            ->latest()
            ->paginate(8);
    }

    public function render()
    {
        return view('livewire.student.quiz-list');
    }
}
