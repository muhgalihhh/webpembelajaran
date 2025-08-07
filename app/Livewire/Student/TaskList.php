<?php

namespace App\Livewire\Student;

use App\Models\Task;
use App\Models\TaskSubmission;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.landing')]
#[Title('Halaman Tugas')]
class TaskList extends Component
{
    use WithPagination, WithFileUploads;

    #[Url(as: 'tab', history: true)]
    public string $activeTab = 'semua';

    // Properti untuk modal
    public ?Task $selectedTask = null;
    public $submissionFile;
    public $submissionNotes;

    // Properti baru untuk melihat detail pengumpulan
    public ?TaskSubmission $viewingSubmission = null;

    protected function rules()
    {
        return [
            'submissionFile' => 'required|file|mimes:pdf,doc,docx,jpg,png,zip|max:5120',
            'submissionNotes' => 'nullable|string',
        ];
    }

    #[Computed]
    public function tasks()
    {
        $student = Auth::user();
        $submittedTaskIds = $student->taskSubmissions->pluck('task_id');

        return Task::where('class_id', $student->class_id)
            ->where('status', 'publish')
            ->with(['subject', 'submissions' => fn($q) => $q->where('user_id', $student->id)])
            ->when($this->activeTab === 'belum', fn($q) => $q->whereNotIn('id', $submittedTaskIds))
            ->when($this->activeTab === 'sudah', fn($q) => $q->whereIn('id', $submittedTaskIds))
            ->latest('due_date')
            ->paginate(5);
    }

    #[Computed]
    public function stats()
    {
        $student = Auth::user();
        $allTasksCount = Task::where('class_id', $student->class_id)->where('status', 'publish')->count();
        $submittedCount = $student->taskSubmissions()->count();

        return [
            'not_submitted' => $allTasksCount - $submittedCount,
            'submitted' => $submittedCount,
        ];
    }

    public function openSubmissionModal(Task $task)
    {
        $this->selectedTask = $task;
        $this->resetValidation();
        $this->reset(['submissionFile', 'submissionNotes']);
        $this->dispatch('open-modal', id: 'submission-modal');
    }

    public function submitTask()
    {
        $this->validate();

        $filePath = $this->submissionFile->store('task_submissions', 'public');

        TaskSubmission::create([
            'task_id' => $this->selectedTask->id,
            'user_id' => Auth::id(),
            'file_path' => $filePath,
            'notes' => $this->submissionNotes,
            'submitted_at' => now(),
            'status' => 'submitted',
        ]);

        $this->reset(['selectedTask', 'submissionFile', 'submissionNotes']);
        $this->dispatch('close-modal');
        $this->dispatch('flash-message', ['message' => 'Tugas berhasil dikumpulkan!', 'type' => 'success']);
    }

    /**
     * Metode baru untuk membuka modal detail pengumpulan.
     */
    public function viewSubmission(int $taskId)
    {
        $this->viewingSubmission = TaskSubmission::where('user_id', Auth::id())
            ->where('task_id', $taskId)
            ->with('task.subject') // Eager load relasi
            ->first();

        if ($this->viewingSubmission) {
            $this->dispatch('open-modal', id: 'view-submission-modal');
        }
    }

    public function render()
    {
        return view('livewire.student.task-list');
    }
}