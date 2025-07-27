<?php

namespace App\Livewire\Teacher;

use App\Models\Task;
use App\Models\TaskSubmission;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.teacher')]
#[Title('Penilaian Pengumpulan Tugas')]
class ScoreTaskSubmissions extends Component
{
    use WithPagination;

    public Task $task;

    // Properti State
    public bool $isScoring = false;
    public ?TaskSubmission $scoringSubmission = null;

    // Properti Form
    #[Rule('required|integer|min:0|max:100', as: 'Nilai')]
    public $score = 0;

    #[Rule('nullable|string', as: 'Umpan Balik')]
    public $feedback = '';

    public function mount(Task $task)
    {
        $this->task = $task;
    }

    #[Computed]
    public function submissions()
    {
        return TaskSubmission::with('student')
            ->where('task_id', $this->task->id)
            ->orderBy('submitted_at', 'desc')
            ->paginate(10);
    }

    private function resetForm()
    {
        $this->reset(['isScoring', 'scoringSubmission', 'score', 'feedback']);
        $this->resetValidation();
    }

    public function score(TaskSubmission $submission)
    {
        $this->isScoring = true;
        $this->scoringSubmission = $submission;
        $this->score = $submission->score ?? 0;
        $this->feedback = $submission->feedback ?? '';
        $this->dispatch('open-modal', id: 'score-form-modal');
    }

    public function saveScore()
    {
        $this->validate();

        if ($this->scoringSubmission) {
            $this->scoringSubmission->update([
                'score' => $this->score,
                'feedback' => $this->feedback,
            ]);

            $this->dispatch('flash-message', message: 'Nilai berhasil disimpan.', type: 'success');
            $this->dispatch('close-modal');
        }
    }

    public function render()
    {
        return view('livewire.teacher.score-task-submissions');
    }
}