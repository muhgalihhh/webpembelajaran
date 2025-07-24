<?php

namespace App\Livewire\Teacher;

use App\Models\Material;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.teacher')]
#[Title('Manajemen Materi')]
class ManageMaterials extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterSubject = '';
    public string $filterPublished = '';
    public $confirmingDeletion = false;
    public $materialToDelete = null; // Add this property to store material ID

    /**
     * Mengambil daftar mata pelajaran dari database untuk filter.
     */
    #[Computed]
    public function subjects()
    {
        return Subject::orderBy('name')->get();
    }

    /**
     * Mengambil data materi dengan filter dan paginasi.
     */
    #[Computed]
    public function materials()
    {
        return Material::query()
            ->with(['subject', 'uploader']) // Menggunakan relasi 'uploader'
            ->where('user_id', Auth::id())
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterSubject, function ($query) {
                $query->where('subject_id', $this->filterSubject);
            })
            ->when($this->filterPublished !== '', function ($query) {
                $query->where('is_published', $this->filterPublished);
            })
            ->latest()
            ->paginate(10);
    }

    /**
     * Confirm deletion of material
     */
    public function confirmDelete($materialId)
    {
        $material = Material::find($materialId);

        if (!$material || $material->user_id !== Auth::id()) {
            session()->flash('flash-message', [
                'message' => 'Anda tidak diizinkan untuk menghapus materi ini.',
                'type' => 'error'
            ]);
            return;
        }

        $this->materialToDelete = $materialId;
        $this->confirmingDeletion = true;
    }

    /**
     * Menghapus materi.
     */
    public function delete()
    {
        if (!$this->materialToDelete) {
            return;
        }

        $material = Material::find($this->materialToDelete);

        if (!$material || $material->user_id !== Auth::id()) {
            session()->flash('flash-message', [
                'message' => 'Anda tidak diizinkan untuk menghapus materi ini.',
                'type' => 'error'
            ]);
            $this->closeConfirmModal();
            return;
        }

        // Hapus file jika ada
        if ($material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        session()->flash('flash-message', [
            'message' => 'Materi berhasil dihapus.',
            'type' => 'success'
        ]);

        $this->closeConfirmModal();
    }

    /**
     * Close confirmation modal
     */
    public function closeConfirmModal()
    {
        $this->confirmingDeletion = false;
        $this->materialToDelete = null;
    }

    public function render()
    {
        return view('livewire.teacher.manage-materials');
    }
}