<?php

namespace App\Livewire\Admin;

use App\Models\Classes;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
#[Title('Manajemen Kelas')]
class ManageClasses extends Component
{
    use WithPagination;

    #[Url(as: 'q', history: true)]
    public $search = '';
    #[Url(history: true)]
    public $sortBy = 'class'; // Default sort by 'class'
    #[Url(history: true)]
    public $sortDirection = 'asc';
    public $perPage = 10;

    // Properti Form disederhanakan
    public $class;
    public $description;

    public $isEditing = false;
    public ?Classes $editingClass = null;
    public $itemToDeleteId = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    protected function rules()
    {
        $classId = $this->editingClass ? $this->editingClass->id : null;
        return [
            'class' => ['required', 'string', 'max:10', Rule::unique('classes')->ignore($classId)],
            'description' => 'nullable|string',
        ];
    }

    #[Computed]
    public function classes()
    {
        return Classes::where('class', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.admin.manage-classes');
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
        $this->reset(['isEditing', 'editingClass', 'class', 'description']);
        $this->resetValidation();
    }

    public function create()
    {
        $this->isEditing = false;
        $this->resetForm();
        $this->dispatch('open-modal', id: 'class-form-modal');
    }

    public function edit(Classes $class)
    {
        $this->isEditing = true;
        $this->editingClass = $class;
        $this->class = $class->class;
        $this->description = $class->description;
        $this->dispatch('open-modal', id: 'class-form-modal');
    }

    public function save()
    {
        $validatedData = $this->validate();
        if ($this->isEditing) {
            $this->editingClass->update($validatedData);
            $message = 'Data kelas berhasil diperbarui.';
        } else {
            Classes::create($validatedData);
            $message = 'Data kelas berhasil ditambahkan.';
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
            Classes::findOrFail($this->itemToDeleteId)->delete();
            $this->dispatch('flash-message', message: 'Data kelas berhasil dihapus.', type: 'success');
        }
        $this->dispatch('close-confirm-modal');
        $this->itemToDeleteId = null;
        $this->resetPage();
    }
}