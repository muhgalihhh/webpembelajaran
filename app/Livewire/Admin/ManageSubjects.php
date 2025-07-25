<?php

namespace App\Livewire\Admin;

use App\Models\Subject;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
#[Title('Manajemen Mata Pelajaran')]
class ManageSubjects extends Component
{
    use WithPagination;

    // Properti untuk state & filter
    #[Url(as: 'q', history: true)]
    public $search = '';

    #[Url(history: true)]
    public $status_filter = ''; // Filter untuk status aktif/nonaktif

    #[Url(history: true)]
    public $sortBy = 'name';

    #[Url(history: true)]
    public $sortDirection = 'asc';

    public $perPage = 10;

    // Properti Form
    public $name, $code;
    public $is_active = true; // Default value

    // Properti untuk state modal & data
    public $isEditing = false;
    public ?Subject $editingSubject = null;
    public $confirmingDeletion = false;
    public $itemToDeleteId = null;

    protected function rules()
    {
        $subjectId = $this->editingSubject?->id;
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('subjects')->ignore($subjectId)],
            'code' => ['required', 'string', 'max:50', Rule::unique('subjects')->ignore($subjectId)],
            'is_active' => 'required|boolean',
        ];
    }

    #[Computed]
    public function subjects()
    {
        return Subject::where('name', 'like', '%' . $this->search . '%')
            ->when($this->status_filter !== '', function ($query) {
                $query->where('is_active', $this->status_filter);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.admin.manage-subjects', [
            'subjects' => $this->subjects(),
        ]);
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortBy = $field;
    }

    private function resetForm()
    {
        $this->reset(['isEditing', 'editingSubject', 'name', 'code', 'is_active']);
        $this->resetValidation();
    }

    public function create()
    {
        $this->isEditing = false;
        $this->resetForm();
        $this->is_active = true; // Set default value
        $this->dispatch('open-modal', id: 'subject-form-modal');
    }

    public function edit(Subject $subject)
    {
        usleep(500000); // Simulasi loading
        $this->isEditing = true;
        $this->editingSubject = $subject;
        $this->name = $subject->name;
        $this->code = $subject->code;
        $this->is_active = $subject->is_active;
        $this->dispatch('open-modal', id: 'subject-form-modal');
    }

    public function save()
    {
        $validatedData = $this->validate();

        if ($this->isEditing) {
            $this->editingSubject->update($validatedData);
            $message = 'Data mata pelajaran berhasil diperbarui.';
        } else {
            Subject::create($validatedData);
            $message = 'Data mata pelajaran berhasil ditambahkan.';
        }

        $this->dispatch('flash-message', message: $message, type: 'success');
        $this->dispatch('close-modal');
    }

    public function confirmDelete($id)
    {
        $this->itemToDeleteId = $id;
        $this->confirmingDeletion = true;
    }

    public function closeConfirmModal()
    {
        $this->confirmingDeletion = false;
        $this->itemToDeleteId = null;
    }

    public function delete()
    {
        Subject::findOrFail($this->itemToDeleteId)->delete();
        $this->dispatch('flash-message', message: 'Data mata pelajaran berhasil dihapus.', type: 'success');
        $this->closeConfirmModal();
        $this->resetPage();
    }
}
