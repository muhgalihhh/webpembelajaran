<?php

namespace App\Livewire\Admin;

use App\Models\Curriculum;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
#[Title('Manajemen Kurikulum')]
class ManageCurriculums extends Component
{
    use WithPagination;

    #[Url(as: 'q', history: true)]
    public $search = '';
    public $perPage = 10;
    public $isEditing = false;
    public ?Curriculum $editingCurriculum = null;
    public $itemToDeleteId = null;

    // Form properties
    public $name;
    public $is_active = true;

    protected function rules()
    {
        $curriculumId = $this->editingCurriculum?->id;
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('curriculums')->ignore($curriculumId)],
            'is_active' => 'required|boolean',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    #[Computed]
    public function curriculums()
    {
        return Curriculum::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('name', 'asc')
            ->paginate($this->perPage);
    }

    private function resetForm()
    {
        $this->reset(['isEditing', 'editingCurriculum', 'name', 'is_active']);
        $this->resetValidation();
    }

    public function create()
    {
        $this->isEditing = false;
        $this->resetForm();
        $this->is_active = true;
        $this->dispatch('open-modal', id: 'curriculum-form-modal');
    }

    public function edit(Curriculum $curriculum)
    {
        $this->isEditing = true;
        $this->editingCurriculum = $curriculum;
        $this->name = $curriculum->name;
        $this->is_active = $curriculum->is_active;
        $this->dispatch('open-modal', id: 'curriculum-form-modal');
    }

    public function save()
    {
        $validatedData = $this->validate();

        if ($this->isEditing) {
            $this->editingCurriculum->update($validatedData);
            $message = 'Kurikulum berhasil diperbarui.';
        } else {
            Curriculum::create($validatedData);
            $message = 'Kurikulum berhasil ditambahkan.';
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
            Curriculum::findOrFail($this->itemToDeleteId)->delete();
            $this->dispatch('flash-message', message: 'Kurikulum berhasil dihapus.', type: 'success');
        }
        $this->dispatch('close-confirm-modal');
        $this->itemToDeleteId = null;
    }

    public function render()
    {
        return view('livewire.admin.manage-curriculums');
    }
}
