<?php

namespace App\Livewire\Teacher;

use App\Models\Classes;
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
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.teacher')]
#[Title('Manajemen Materi')]
class ManageMaterials extends Component
{
    use WithPagination;

    // Properti filter
    #[Url(as: 'q', history: true)]
    public string $search = '';
    #[Url(as: 'mapel', history: true)]
    public string $filterSubject = '';
    #[Url(as: 'kelas', history: true)]
    public string $filterClass = '';
    #[Url(as: 'status', history: true)]
    public string $filterPublished = '';
    public $itemToDeleteId = null;

    // Properti untuk state modal
    public ?Material $viewingMaterial = null;

    // Properti sorting
    #[Url(history: true)]
    public string $sortBy = 'title';
    #[Url(history: true)]
    public string $sortDirection = 'asc';

    // Lifecycle hooks
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingFilterSubject()
    {
        $this->resetPage();
    }
    public function updatingFilterClass()
    {
        $this->resetPage();
    }
    public function updatingFilterPublished()
    {
        $this->resetPage();
    }

    #[Computed]
    public function subjects()
    {
        return Subject::orderBy('name')->get();
    }

    #[Computed]
    public function classes()
    {
        return Classes::orderBy('class')->get();
    }

    #[Computed]
    public function materials()
    {
        return Material::query()
            ->with(['subject', 'uploader', 'class'])
            ->where('user_id', Auth::id())
            ->when($this->search, fn($query) => $query->where('title', 'like', '%' . $this->search . '%'))
            ->when($this->filterSubject, fn($query) => $query->where('subject_id', $this->filterSubject))
            ->when($this->filterClass, fn($query) => $query->where('class_id', $this->filterClass))
            ->when($this->filterPublished !== '', fn($query) => $query->where('is_published', $this->filterPublished))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->latest()
            ->paginate(10);
    }

    public function sortBy(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortBy = $field;
    }

    // Method baru untuk menampilkan modal detail
    public function view(Material $material)
    {
        $this->viewingMaterial = $material;
        $this->dispatch('open-material-preview-modal');
    }

    public function confirmDelete($materialId)
    {
        $this->itemToDeleteId = $materialId;
        $this->dispatch('open-confirm-modal');
    }

    public function delete()
    {
        if (!$this->itemToDeleteId)
            return;

        $material = Material::find($this->itemToDeleteId);
        if (!$material || $material->user_id !== Auth::id()) {
            $this->dispatch('flash-message', ['message' => 'Materi tidak ditemukan atau tidak dapat dihapus.', 'type' => 'error']);
            $this->dispatch('close-confirm-modal');
            return;
        }

        if ($material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }
        $material->delete();

        $this->dispatch('flash-message', ['message' => 'Materi berhasil dihapus.', 'type' => 'success']);
        $this->dispatch('close-confirm-modal');
        $this->itemToDeleteId = null;
    }

    public function download(int $materialId): ?StreamedResponse
    {
        $material = Material::find($materialId);
        if (!$material || $material->user_id !== Auth::id() || !$material->file_path) {
            $this->dispatch('flash-message', ['message' => 'Materi tidak ditemukan atau tidak dapat diunduh.', 'type' => 'error']);
            return null;
        }
        if (!Storage::disk('public')->exists($material->file_path)) {
            $this->dispatch('flash-message', ['message' => 'File materi tidak ditemukan.', 'type' => 'error']);
            return null;
        }

        $this->dispatch('flash-message', ['message' => 'Sedang mengunduh materi...', 'type' => 'info']);

        return Storage::disk('public')->download($material->file_path);
    }

    public function render()
    {
        return view('livewire.teacher.manage-materials');
    }
}