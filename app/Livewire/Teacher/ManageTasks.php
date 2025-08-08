<?php

namespace App\Livewire\Teacher;

use App\Models\Classes;
use App\Models\Subject;
use App\Models\Task;
use App\Notifications\NotificationStudent;
use App\Services\WhatsAppNotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.teacher')]
#[Title('Manajemen Tugas')]
class ManageTasks extends Component
{
    use WithPagination, WithFileUploads;

    // Properti untuk filter dan URL
    #[Url(as: 'q')]
    public string $search = '';
    #[Url(as: 'mapel')]
    public string $subjectFilter = '';
    #[Url(as: 'kelas')]
    public string $classFilter = '';
    #[Url(as: 'status_tugas')]
    public string $statusFilter = '';
    public $itemToDeleteId = null;

    // Properti untuk sorting
    #[Url]
    public string $sortBy = 'created_at';
    #[Url]
    public string $sortDirection = 'desc';

    // Properti state
    public bool $isEditing = false;
    public ?Task $editingTask = null;

    // Properti Form
    #[Rule('required|string|max:255')]
    public string $title = '';
    #[Rule('required|string')]
    public string $description = '';
    #[Rule('required|exists:subjects,id')]
    public $subject_id = '';
    #[Rule('required|exists:classes,id')]
    public $class_id = '';
    #[Rule('nullable|date_format:Y-m-d\TH:i')]
    public $due_time;
    #[Rule('required|boolean')]
    public bool $is_published = false;
    #[Rule('nullable|date_format:Y-m-d\TH:i|after_or_equal:now', message: 'Jadwal tidak boleh di masa lalu.')]
    public $published_at;
    #[Rule('nullable|file|mimes:pdf,doc,docx,jpg,png,zip,rar|max:5120')]
    public $uploadedFile;
    public ?string $currentFilePath = null;

    // Lifecycle hooks
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
    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    #[Computed]
    public function tasks()
    {
        return Task::with(['subject', 'class', 'creator'])
            ->where('user_id', Auth::id())
            ->when($this->search, fn($q) => $q->where('title', 'like', '%' . $this->search . '%'))
            ->when($this->subjectFilter, fn($q) => $q->where('subject_id', $this->subjectFilter))
            ->when($this->classFilter, fn($q) => $q->where('class_id', $this->classFilter))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);
    }

    #[Computed]
    public function subjects()
    {
        return Subject::orderBy('kurikulum', 'asc')->orderBy('name')->get()
            ->mapWithKeys(function ($subject) {
                $displayText = "{$subject->name} - ({$subject->kurikulum})";
                return [$subject->id => $displayText];
            });
    }

    #[Computed]
    public function classes()
    {
        return Classes::orderBy('class')->get();
    }

    public function sortBy(string $field): void
    {
        $this->sortDirection = $this->sortBy === $field ? ($this->sortDirection === 'asc' ? 'desc' : 'asc') : 'asc';
        $this->sortBy = $field;
    }

    private function resetForm()
    {
        $this->reset(['isEditing', 'editingTask', 'title', 'description', 'subject_id', 'class_id', 'due_time', 'is_published', 'published_at', 'uploadedFile', 'currentFilePath']);
        $this->resetValidation();
    }

    public function create()
    {
        $this->isEditing = false;
        $this->resetForm();
        $this->is_published = false;
        $this->dispatch('open-modal', id: 'task-form-modal');
    }

    public function edit(Task $task)
    {
        $this->isEditing = true;
        $this->editingTask = $task;
        $this->title = $task->title;
        $this->description = $task->description;
        $this->subject_id = $task->subject_id;
        $this->class_id = $task->class_id;

        // Menggunakan accessor `due_date_time` dari model Task untuk menghindari error
        $this->due_time = $task->due_date_time ? $task->due_date_time->format('Y-m-d\TH:i') : null;

        $this->is_published = $task->is_published;
        $this->published_at = $task->published_at ? $task->published_at->format('Y-m-d\TH:i') : null;
        $this->currentFilePath = $task->attachment_path;
        $this->dispatch('open-modal', id: 'task-form-modal');
    }

    public function save()
    {
        $validatedData = $this->validate();
        $validatedData['user_id'] = Auth::id();
        $validatedData['is_published'] = (bool) $this->is_published;

        $validatedData['status'] = $validatedData['is_published'] ? 'publish' : 'draft';

        if ($this->uploadedFile) {
            if ($this->isEditing && $this->editingTask->attachment_path) {
                Storage::disk('public')->delete($this->editingTask->attachment_path);
            }
            $validatedData['attachment_path'] = $this->uploadedFile->store('task-attachments', 'public');
        }

        if (!empty($this->due_time)) {
            $dueCarbon = \Carbon\Carbon::parse($this->due_time);
            $validatedData['due_date'] = $dueCarbon->toDateString();
            $validatedData['due_time'] = $dueCarbon->toTimeString();
        } else {
            $validatedData['due_date'] = null;
            $validatedData['due_time'] = null;
        }

        if ($this->isEditing) {
            $this->editingTask->update($validatedData);
            $message = 'Tugas berhasil diperbarui.';
        } else {
            $task = Task::create($validatedData);
            $message = 'Tugas berhasil ditambahkan.';
            if ($task->is_published && !$task->published_at) {
                $this->sendNewTaskNotification($task);
            }
        }

        $this->dispatch('flash-message', message: $message, type: 'success');
        $this->dispatch('close-modal');
    }

    private function sendNewTaskNotification(Task $task)
    {
        try {
            $task->load('subject', 'class');
            $class = $task->class;

            if ($class && $class->whatsapp_group_id) {
                $subjectName = $task->subject->name;
                $className = $class->class;

                $students = $class->users;

                $dueDate = 'Tanpa Batas Waktu';
                if ($task->due_date_time) {
                    $dueDate = $task->due_date_time->format('d F Y, H:i');
                }

                if ($students->isNotEmpty()) {
                    Notification::send($students, new NotificationStudent($task));
                }

                $waMessage = "🔔 *Notifikasi Tugas Baru* 🔔\n\n" .
                    "Halo siswa kelas *{$className}*!\n\n" .
                    "Ada tugas baru untuk mata pelajaran *{$subjectName}* dengan judul:\n" .
                    "*\"{$task->title}\"*\n\n" .
                    "Batas pengumpulan: *{$dueDate}*.\n\n" .
                    "Yuk, segera cek dan kerjakan di web pembelajaran ya! Semangat! 💪";


                $notificationService = new WhatsAppNotificationService();
                $notificationService->sendMessage($class->whatsapp_group_id, $waMessage);

            }
        } catch (\Exception $e) {
            $this->dispatch('flash-message', message: 'Tugas berhasil ditambahkan, tetapi gagal mengirim notifikasi.', type: 'warning');

        }
    }

    public function confirmDelete($id)
    {
        $this->itemToDeleteId = $id;
        $this->dispatch('open-confirm-modal');
    }

    public function delete()
    {
        $task = Task::find($this->itemToDeleteId);
        if ($task && $task->user_id === Auth::id()) {
            if ($task->attachment_path) {
                Storage::disk('public')->delete($task->attachment_path);
            }
            $task->delete();
            $this->dispatch('flash-message', message: 'Tugas berhasil dihapus.', type: 'success');
        } else {
            $this->dispatch('flash-message', message: 'Gagal menghapus tugas.', type: 'error');
        }
        $this->itemToDeleteId = null;
        $this->dispatch('close-confirm-modal');
    }

    public function render()
    {
        return view('livewire.teacher.manage-tasks');
    }
}