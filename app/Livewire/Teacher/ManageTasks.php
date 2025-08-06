<?php

namespace App\Livewire\Teacher;

use App\Models\Classes;
use App\Models\Subject;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
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

    // Properti Filter
    #[Url(as: 'q')]
    public string $search = '';
    #[Url(as: 'mapel')]
    public string $subjectFilter = '';
    #[Url(as: 'kelas')]
    public string $classFilter = '';
    #[Url(as: 'status')]
    public string $statusFilter = '';
    public $itemToDeleteId = null;

    // Properti Sorting
    #[Url]
    public string $sortBy = 'due_time';
    #[Url]
    public string $sortDirection = 'desc';

    // Properti State
    public bool $isEditing = false;
    public ?Task $editingTask = null;

    // Properti Form (disesuaikan dengan model dan database)
    #[Rule('required|string|max:255')]
    public string $title = '';
    #[Rule('required|string')]
    public string $description = '';
    #[Rule('required|exists:subjects,id')]
    public $subject_id = '';
    #[Rule('required|exists:classes,id')]
    public $class_id = '';
    #[Rule('required|date')]
    public $due_time;

    // --- PERBAIKAN: Menyesuaikan aturan validasi status dengan database ---
    #[Rule('required|in:draft,publish')]
    public string $status = 'draft';

    #[Rule('required|boolean')]
    public bool $is_published = false;
    #[Rule('nullable|date|required_if:is_published,true')]
    public $published_at;
    #[Rule('nullable|file|mimes:pdf,doc,docx,jpg,png,zip|max:5120')]
    public $uploadedFile;
    public ?string $currentFilePath = null;

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
    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    #[Computed]
    public function tasks()
    {
        return Task::with(['subject', 'class'])
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
        return Classes::orderBy('class')->get()->pluck('class', 'id');
    }

    public function sortBy(string $field): void
    {
        $this->sortDirection = $this->sortBy === $field ? ($this->sortDirection === 'asc' ? 'desc' : 'asc') : 'asc';
        $this->sortBy = $field;
    }

    private function resetForm()
    {
        $this->reset(['isEditing', 'editingTask', 'title', 'description', 'subject_id', 'class_id', 'due_time', 'status', 'is_published', 'published_at', 'uploadedFile', 'currentFilePath']);
        $this->resetValidation();
    }

    public function create()
    {
        $this->isEditing = false;
        $this->resetForm();
        $this->status = 'draft';
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
        $this->due_time = $task->due_time ? $task->due_time->format('Y-m-d\TH:i') : null;
        $this->status = $task->status;
        $this->is_published = $task->is_published;
        $this->published_at = $task->published_at ? $task->published_at->format('Y-m-d\TH:i') : null;
        $this->currentFilePath = $task->attachment_path;
        $this->dispatch('open-modal', id: 'task-form-modal');
    }

    public function save()
    {
        $validatedData = $this->validate();
        $validatedData['user_id'] = Auth::id();
        $validatedData['due_date'] = $this->due_time ? date('Y-m-d', strtotime($this->due_time)) : null;

        if ($this->uploadedFile) {
            if ($this->isEditing && $this->editingTask->attachment_path) {
                Storage::disk('public')->delete($this->editingTask->attachment_path);
            }
            $validatedData['attachment_path'] = $this->uploadedFile->store('task-attachments', 'public');
        }

        if ($this->isEditing) {
            $this->editingTask->update($validatedData);
            $message = 'Tugas berhasil diperbarui.';
        } else {
            Task::create($validatedData);
            $message = 'Tugas berhasil ditambahkan.';
        }
        $this->dispatch('flash-message', message: $message, type: 'success');
        $this->dispatch('close-modal');
    }

    public function confirmDelete($id)
    {
        $this->itemToDeleteId = $id;
        $this->dispatch('open-confirm-modal');
    }

    public function delete()
    {
        if ($this->itemToDeleteId) {
            $task = Task::find($this->itemToDeleteId);
            if ($task && $task->user_id === Auth::id()) {
                if ($task->attachment_path) {
                    Storage::disk('public')->delete($task->attachment_path);
                }
                $task->delete();
                $this->dispatch('flash-message', message: 'Tugas berhasil dihapus.', type: 'success');
            }
        }
        $this->dispatch('close-confirm-modal');
        $this->itemToDeleteId = null;
    }

    public function render()
    {
        return view('livewire.teacher.manage-tasks');
    }
}
