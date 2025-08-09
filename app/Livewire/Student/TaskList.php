<?php

namespace App\Livewire\Student;

use App\Models\Curriculum;
use App\Models\Subject;
use App\Models\Task;
use App\Models\TaskSubmission;
use App\Notifications\NotificationTeacher; // <-- 1. Tambahkan use statement ini
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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

    #[Url(as: 'mapel')]
    public string $subjectFilter = '';
    #[Url(as: 'kurikulum')]
    public string $kurikulumFilter = '';

    public ?Task $selectedTask = null;
    public $submissionFile;
    public $submissionNotes;

    public ?TaskSubmission $viewingSubmission = null;
    public ?string $fileViewerUrl = null;
    public ?string $fileViewerType = null;
    public ?string $fileViewerTitle = null;

    public function updatingSubjectFilter()
    {
        $this->resetPage();
    }
    public function updatingKurikulumFilter()
    {
        $this->resetPage();
        $this->reset('subjectFilter');
    }

    protected function rules()
    {
        return [
            'submissionFile' => 'required|file|mimes:pdf,doc,docx,jpg,png,zip,rar|max:5120',
            'submissionNotes' => 'nullable|string|max:1000',
        ];
    }

    #[Computed]
    public function tasks()
    {
        $student = Auth::user();
        if (!$student->class_id) {
            return collect();
        }

        $submittedTaskIds = $student->taskSubmissions->pluck('task_id');

        return Task::where('class_id', $student->class_id)
            ->where('status', 'publish')
            ->with(['subject', 'submissions' => fn($q) => $q->where('user_id', $student->id)])
            ->when($this->subjectFilter, fn($q) => $q->where('subject_id', this->subjectFilter))
            ->whereHas('subject', function ($query) {
                $query->when($this->kurikulumFilter, function ($q) {
                    $q->where('kurikulum', $this->kurikulumFilter);
                });
            })
            ->when($this->activeTab === 'belum', fn($q) => $q->whereNotIn('id', $submittedTaskIds))
            ->when($this->activeTab === 'sudah', fn($q) => $q->whereIn('id', $submittedTaskIds))
            ->latest('due_date')
            ->paginate(5);
    }

    #[Computed]
    public function stats()
    {
        $student = Auth::user();
        if (!$student->class_id) {
            return ['not_submitted' => 0, 'submitted' => 0];
        }

        $allTasksCount = Task::where('class_id', $student->class_id)->where('status', 'publish')->count();
        $submittedCount = $student->taskSubmissions()->count();

        return [
            'not_submitted' => $allTasksCount - $submittedCount,
            'submitted' => $submittedCount,
        ];
    }

    #[Computed]
    public function kurikulumOptions()
    {
        return Curriculum::where('is_active', true)->pluck('name', 'name')->all();
    }

    #[Computed]
    public function subjects()
    {
        return Subject::query()
            ->when($this->kurikulumFilter, fn($q) => $q->where('kurikulum', $this->kurikulumFilter))
            ->orderBy('name')
            ->get();
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

        // --- 👇 Blok Kode Notifikasi untuk Guru Dimulai Di Sini 👇 ---

        // 2. Dapatkan data guru dari tugas yang dipilih
        $teacher = $this->selectedTask->teacher;
        $student = Auth::user();

        // 3. Kirim notifikasi jika guru ditemukan
        if ($teacher) {
            $teacher->notify(new NotificationTeacher($student, $this->selectedTask, 'task_submission'));
        }

        // --- 👆 Blok Kode Notifikasi Selesai 👆 ---

        $this->reset(['selectedTask', 'submissionFile', 'submissionNotes']);
        $this->dispatch('close-modal');
        $this->dispatch('flash-message', message: 'Tugas berhasil dikumpulkan!', type: 'success');
    }

    public function viewSubmission(int $taskId)
    {
        $this->viewingSubmission = TaskSubmission::where('user_id', Auth::id())
            ->where('task_id', $taskId)
            ->with('task.subject')
            ->first();

        if ($this->viewingSubmission) {
            $this->dispatch('open-modal', id: 'view-submission-modal');
        }
    }

    public function viewFile(int $submissionId)
    {
        $submission = TaskSubmission::find($submissionId);

        if (!$submission || !$submission->file_path || !Storage::disk('public')->exists($submission->file_path)) {
            $this->dispatch('flash-message', message: 'File tidak ditemukan.', type: 'error');
            return;
        }

        $this->fileViewerTitle = 'Jawaban: ' . basename($submission->file_path);
        $mimeType = Storage::disk('public')->mimeType($submission->file_path);

        if ($mimeType === 'application/pdf') {
            $this->fileViewerType = 'pdf';
            $this->fileViewerUrl = Storage::url($submission->file_path);
            $this->dispatch('open-modal', id: 'file-viewer-modal');
        } elseif (str_starts_with($mimeType, 'image/')) {
            $this->fileViewerType = 'image';
            $this->fileViewerUrl = Storage::url($submission->file_path);
            $this->dispatch('open-modal', id: 'file-viewer-modal');
        } else {
            return Storage::disk('public')->download($submission->file_path);
        }
    }

    public function closeFileViewer()
    {
        $this->reset(['fileViewerUrl', 'fileViewerType', 'fileViewerTitle']);
        $this->dispatch('close-modal');
    }

    public function render()
    {
        return view('livewire.student.task-list');
    }
}