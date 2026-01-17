<?php

namespace App\Livewire\Student;

use App\Models\Curriculum;
use App\Models\Subject;
use App\Models\Task;
use App\Models\TaskSubmission;
use App\Notifications\NotificationTeacher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
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
    public string $submissionNotes = '';
    public bool $isSubmitting = false;

    public ?TaskSubmission $viewingSubmission = null;
    public ?string $fileViewerUrl = null;
    public ?string $fileViewerType = null;
    public ?string $fileViewerTitle = null;

    protected $listeners = [
        'refreshTasks' => '$refresh'
    ];

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
            'submissionFile' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png,zip,rar,txt|max:10240', // 10MB
            'submissionNotes' => 'nullable|string|max:1000',
        ];
    }

    protected function messages()
    {
        return [
            'submissionFile.required' => 'File tugas wajib diunggah.',
            'submissionFile.file' => 'File yang diunggah tidak valid.',
            'submissionFile.mimes' => 'Format file harus: PDF, DOC, DOCX, JPG, JPEG, PNG, ZIP, RAR, atau TXT.',
            'submissionFile.max' => 'Ukuran file maksimal 10 MB.',
            'submissionNotes.max' => 'Catatan maksimal 1000 karakter.',
        ];
    }

    #[Computed]
    public function tasks()
    {
        $student = Auth::user();
        
        if (!$student->class_id) {
            return collect();
        }

        // Get submitted task IDs more efficiently
        $submittedTaskIds = TaskSubmission::where('user_id', $student->id)
            ->pluck('task_id');

        $query = Task::where('class_id', $student->class_id)
            ->where('status', 'publish')
            ->with([
                'subject:id,name,kurikulum', 
                'submissions' => fn($q) => $q->where('user_id', $student->id)->select('id', 'task_id', 'user_id', 'score', 'status')
            ])
            ->select('id', 'title', 'description','attachment_path', 'subject_id', 'due_date', 'due_time', 'created_at');

        // Apply filters
        if ($this->subjectFilter) {
            $query->where('subject_id', $this->subjectFilter);
        }

        if ($this->kurikulumFilter) {
            $query->whereHas('subject', fn($q) => $q->where('kurikulum', $this->kurikulumFilter));
        }

        // Apply tab filtering
        switch ($this->activeTab) {
            case 'belum':
                $query->whereNotIn('id', $submittedTaskIds);
                break;
            case 'sudah':
                $query->whereIn('id', $submittedTaskIds);
                break;
        }

        return $query->latest('due_date')->paginate(5);
    }
    public function viewTaskAttachment(int $taskId)
{
    $task = Task::find($taskId);

    if (!$task || !$task->attachment_path) {
        $this->dispatch('flash-message', 
            message: 'Lampiran tidak ditemukan.', 
            type: 'error'
        );
        return;
    }

    if (!Storage::disk('public')->exists($task->attachment_path)) {
        $this->dispatch('flash-message', 
            message: 'File lampiran sudah tidak tersedia di server.', 
            type: 'error'
        );
        return;
    }

    try {
        $this->fileViewerTitle = basename($task->attachment_path);
        $mimeType = Storage::disk('public')->mimeType($task->attachment_path);

        if ($mimeType === 'application/pdf') {
            $this->fileViewerType = 'pdf';
            $this->fileViewerUrl = Storage::url($task->attachment_path);
            $this->dispatch('open-modal', id: 'file-viewer-modal');
        } elseif (str_starts_with($mimeType, 'image/')) {
            $this->fileViewerType = 'image';
            $this->fileViewerUrl = Storage::url($task->attachment_path);
            $this->dispatch('open-modal', id: 'file-viewer-modal');
        } else {
            // fallback: langsung download
            return Storage::disk('public')->download(
                $task->attachment_path,
                basename($task->attachment_path)
            );
        }
    } catch (\Exception $e) {
        Log::error('View attachment failed', [
            'task_id' => $taskId,
            'error' => $e->getMessage(),
        ]);
        $this->dispatch('flash-message', 
            message: 'Tidak dapat membuka lampiran.', 
            type: 'error'
        );
    }
}

public function downloadTaskAttachment(int $taskId)
{
    $task = Task::find($taskId);

    if (!$task || !$task->attachment_path) {
        $this->dispatch('flash-message', 
            message: 'Lampiran tidak ditemukan.', 
            type: 'error'
        );
        return;
    }

    if (!Storage::disk('public')->exists($task->attachment_path)) {
        $this->dispatch('flash-message', 
            message: 'File lampiran sudah tidak tersedia di server.', 
            type: 'error'
        );
        return;
    }

    return Storage::disk('public')->download(
        $task->attachment_path,
        basename($task->attachment_path)
    );
}


    #[Computed]
    public function stats()
    {
        $student = Auth::user();
        
        if (!$student->class_id) {
            return ['not_submitted' => 0, 'submitted' => 0];
        }

        // Use single query to get both counts
        $counts = DB::table('tasks')
            ->leftJoin('task_submissions', function($join) use ($student) {
                $join->on('tasks.id', '=', 'task_submissions.task_id')
                     ->where('task_submissions.user_id', '=', $student->id);
            })
            ->where('tasks.class_id', $student->class_id)
            ->where('tasks.status', 'publish')
            ->selectRaw('
                COUNT(*) as total,
                COUNT(task_submissions.id) as submitted
            ')
            ->first();

        return [
            'not_submitted' => $counts->total - $counts->submitted,
            'submitted' => $counts->submitted,
        ];
    }

    #[Computed]
    public function kurikulumOptions()
    {
        return Curriculum::where('is_active', true)
            ->orderBy('name')
            ->pluck('name', 'name')
            ->all();
    }

    #[Computed]
    public function subjects()
    {
        return Subject::select('id', 'name', 'kurikulum')
            ->orderBy('kurikulum')
            ->orderBy('name')
            ->get()
            ->mapWithKeys(function ($subject) {
                $displayText = "{$subject->name} - ({$subject->kurikulum})";
                return [$subject->id => $displayText];
            });
    }

    public function openSubmissionModal(Task $task)
    {
        // Check if task is overdue
        if ($task->due_date_time && $task->due_date_time->isPast()) {
            $this->dispatch('flash-message', 
                message: 'Tugas ini sudah melewati batas waktu pengumpulan.', 
                type: 'error'
            );
            return;
        }

        // Check if already submitted
        $existingSubmission = TaskSubmission::where('task_id', $task->id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($existingSubmission) {
            $this->dispatch('flash-message', 
                message: 'Tugas ini sudah pernah dikumpulkan.', 
                type: 'warning'
            );
            return;
        }

        $this->selectedTask = $task;
        $this->resetValidation();
        $this->reset(['submissionFile', 'submissionNotes', 'isSubmitting']);
        $this->dispatch('open-modal', id: 'submission-modal');
    }

    public function submitTask()
    {
        if ($this->isSubmitting) {
            return;
        }

        $this->isSubmitting = true;

        try {
            $this->validate();

            // Double-check if task exists and is still available
            if (!$this->selectedTask || $this->selectedTask->status !== 'publish') {
                throw new \Exception('Tugas tidak tersedia.');
            }

            // Check for duplicate submission
            $existingSubmission = TaskSubmission::where('task_id', $this->selectedTask->id)
                ->where('user_id', Auth::id())
                ->exists();

            if ($existingSubmission) {
                throw new \Exception('Tugas sudah pernah dikumpulkan sebelumnya.');
            }

            // Check if overdue
            if ($this->selectedTask->due_date_time && $this->selectedTask->due_date_time->isPast()) {
                throw new \Exception('Tugas sudah melewati batas waktu pengumpulan.');
            }

            DB::beginTransaction();

            // Store file with better naming
            $originalName = $this->submissionFile->getClientOriginalName();
            $extension = $this->submissionFile->getClientOriginalExtension();
            $fileName = 'task_' . $this->selectedTask->id . '_user_' . Auth::id() . '_' . time() . '.' . $extension;
            
            $filePath = $this->submissionFile->storeAs('task_submissions', $fileName, 'public');

            // Create submission record
            $submission = TaskSubmission::create([
                'task_id' => $this->selectedTask->id,
                'user_id' => Auth::id(),
                'file_path' => $filePath,
                'original_filename' => $originalName,
                'notes' => $this->submissionNotes ?: null,
                'submission_date' => now(),
                'status' => 'submitted',
            ]);

            // Send notification to teacher
            $teacher = $this->selectedTask->creator;
            if ($teacher) {
                try {
                    Notification::send($teacher, new NotificationTeacher($submission));
                } catch (\Exception $e) {
                    Log::warning('Failed to send notification to teacher', [
                        'teacher_id' => $teacher->id,
                        'submission_id' => $submission->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            DB::commit();

            $this->reset(['selectedTask', 'submissionFile', 'submissionNotes', 'isSubmitting']);
            $this->dispatch('close-modal');
            $this->dispatch('flash-message', 
                message: 'Tugas berhasil dikumpulkan!', 
                type: 'success'
            );

        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->isSubmitting = false;
            throw $e;
        } catch (\Exception $e) {
            DB::rollback();
            $this->isSubmitting = false;
            
            Log::error('Task submission failed', [
                'user_id' => Auth::id(),
                'task_id' => $this->selectedTask?->id,
                'error' => $e->getMessage()
            ]);
            
            $this->dispatch('flash-message', 
                message: 'Terjadi kesalahan: ' . $e->getMessage(), 
                type: 'error'
            );
        }
    }

    public function viewSubmission(int $taskId)
    {
        $this->viewingSubmission = TaskSubmission::where('user_id', Auth::id())
            ->where('task_id', $taskId)
            ->with(['task:id,title', 'task.subject:id,name'])
            ->first();

        if ($this->viewingSubmission) {
            $this->dispatch('open-modal', id: 'view-submission-modal');
        } else {
            $this->dispatch('flash-message', 
                message: 'Data pengumpulan tugas tidak ditemukan.', 
                type: 'error'
            );
        }
    }

    public function viewFile(int $submissionId)
    {
        $submission = TaskSubmission::where('id', $submissionId)
            ->where('user_id', Auth::id()) // Security check
            ->first();

        if (!$submission || !$submission->file_path) {
            $this->dispatch('flash-message', 
                message: 'File tidak ditemukan.', 
                type: 'error'
            );
            return;
        }

        if (!Storage::disk('public')->exists($submission->file_path)) {
            $this->dispatch('flash-message', 
                message: 'File telah dihapus dari server.', 
                type: 'error'
            );
            return;
        }

        try {
            $this->fileViewerTitle = $submission->original_filename ?: basename($submission->file_path);
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
                // For other file types, trigger download
                return Storage::disk('public')->download(
                    $submission->file_path,
                    $submission->original_filename ?: basename($submission->file_path)
                );
            }
        } catch (\Exception $e) {
            Log::error('File viewing failed', [
                'submission_id' => $submissionId,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            
            $this->dispatch('flash-message', 
                message: 'Tidak dapat membuka file.', 
                type: 'error'
            );
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