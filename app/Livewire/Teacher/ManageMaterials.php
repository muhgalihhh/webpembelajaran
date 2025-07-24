<?php

namespace App\Livewire\Teacher;

use App\Models\Material;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.teacher')]
#[Title('Materi Pembelajaran')]
class ManageMaterials extends Component
{
    use WithPagination;

    // Properti untuk state & filter
    #[Url(as: 'q', history: true)]
    public $search = '';

    #[Url(history: true)]
    public $subjectFilter = '';

    #[Url(history: true)]
    public $sortBy = 'created_at';

    #[Url(history: true)]
    public $sortDirection = 'desc';

    public $perPage = 10;

    // Properti untuk konfirmasi hapus
    public $confirmingDeletion = false;
    public $itemToDeleteId = null;

    #[Computed]
    public function materials()
    {
        $teacherId = Auth::id();
        $subjectIds = Subject::where('teacher_id', $teacherId)->pluck('id');

        return Material::with('subject')
            ->whereIn('subject_id', $subjectIds)
            ->where('title', 'like', '%' . $this->search . '%')
            ->when($this->subjectFilter, function ($query) {
                $query->where('subject_id', $this->subjectFilter);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    #[Computed]
    public function subjects()
    {
        return Subject::orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.teacher.manage-materials');
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
        $material = Material::findOrFail($this->itemToDeleteId);

        if ($material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        $this->dispatch('flash-message', message: 'Materi berhasil dihapus.', type: 'success');
        $this->closeConfirmModal();
        $this->resetPage();
    }
}
