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
    #[Rule('required|in:draft,publish')]
    public string $status = 'draft';
    #[Rule('nullable|date_format:Y-m-d\TH:i|after_or_equal:now', message: 'Jadwal tidak boleh di masa lalu.')]
    public $published_at;
    #[Rule('nullable|file|mimes:pdf,doc,docx,jpg,png,zip,rar|max:5120')]
    public $uploadedFile;
    public ?string $currentFilePath = null;

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

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSubjectFilter()
    {
        $this->resetPage();
    }

    public function updatedClassFilter()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        $this->sortDirection = $this->sortBy === $field ? ($this->sortDirection === 'asc' ? 'desc' : 'asc') : 'asc';
        $this->sortBy = $field;
    }

    private function resetForm()
    {
        $this->reset(['isEditing', 'editingTask', 'title', 'description', 'subject_id', 'class_id', 'due_time', 'status', 'published_at', 'uploadedFile', 'currentFilePath']);
        $this->resetValidation();
    }

    public function create()
    {
        $this->isEditing = false;
        $this->resetForm();
        $this->status = 'draft';
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
        $this->due_time = $task->due_date_time ? $task->due_date_time->format('Y-m-d\TH:i') : null;
        $this->status = $task->status;
        $this->published_at = $task->published_at ? $task->published_at->format('Y-m-d\TH:i') : null;
        $this->currentFilePath = $task->attachment_path;
        $this->dispatch('open-modal', id: 'task-form-modal');
    }

    public function save()
    {
        $validatedData = $this->validate();
        $validatedData['user_id'] = Auth::id();
        $validatedData['status'] = $this->status;

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


        $wasPreviouslyPublished = $this->isEditing ? $this->editingTask->status === 'publish' : false;

        if ($this->isEditing) {
            $this->editingTask->update($validatedData);
            $task = $this->editingTask->fresh(); // Ambil data terbaru dari database
            $message = 'Tugas berhasil diperbarui.';
        } else {
            $task = Task::create($validatedData);
            $message = 'Tugas berhasil ditambahkan.';
        }

        // 2. Tentukan apakah notifikasi perlu dikirim dan apa tipenya
        $isNowPublished = $task->status === 'publish';
        $notificationType = null;

        if ($isNowPublished) {
            if (!$wasPreviouslyPublished) {
                // Dari draft menjadi publish, atau tugas baru yang langsung publish
                $notificationType = 'new';
                $message .= ' Notifikasi telah dikirim ke siswa.';
            } elseif ($wasPreviouslyPublished) {
                // Tugas yang sudah publish diedit (tetap publish)
                $notificationType = 'updated';
                $message .= ' Notifikasi pembaruan telah dikirim ke siswa.';
            }
        }

        // 3. Kirim notifikasi jika ada tipe yang ditentukan
        if ($notificationType) {
            $this->sendTaskNotification($task, $notificationType);
        }


        $this->dispatch('$refresh');
        $this->dispatch('flash-message', message: $message, type: 'success');
        $this->dispatch('close-modal');
    }


    private function sendTaskNotification(Task $task, string $actionType)
    {
        try {
            $task->load('subject', 'class');
            $class = $task->class;
            $students = $class?->users()->whereHas('roles', fn($q) => $q->where('name', 'siswa'))->get();

            if ($students && $students->isNotEmpty()) {

                Notification::send($students, new NotificationStudent($task, $actionType));


                if ($class->whatsapp_group_id && $actionType === 'new') {
                    $subjectName = $task->subject->name;
                    $className = $class->class;
                    $dueDate = $task->due_date_time ? $task->due_date_time->format('d F Y, H:i') : 'Tanpa Batas Waktu';


                    $waMessage = "🔔 *Notifikasi Tugas Baru* 🔔\n\n" .
                        "Halo siswa kelas *{$className}*!\n\n" .
                        "Ada tugas baru untuk mata pelajaran *{$subjectName}* dengan judul:\n" .
                        "*\"{$task->title}\"*\n\n" .
                        "Batas pengumpulan: *{$dueDate}*.\n\n" .
                        "Yuk, segera cek dan kerjakan di web pembelajaran ya! Semangat! 💪";

                    $notificationService = new WhatsAppNotificationService();
                    $notificationService->sendMessage($class->whatsapp_group_id, $waMessage);
                }
            }
        } catch (\Exception $e) {
            $this->dispatch('flash-message', message: 'Tugas berhasil disimpan, tetapi gagal mengirim notifikasi. Error: ' . $e->getMessage(), type: 'warning');
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
            $this->dispatch('$refresh');
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
