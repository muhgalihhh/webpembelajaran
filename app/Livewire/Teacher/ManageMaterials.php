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
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.teacher')]
#[Title('Manajemen Materi')]
class ManageMaterials extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterSubject = '';
    public string $filterPublished = '';
    public $confirmingDeletion = false;
    public $materialToDelete = null;


    public string $sortBy = 'title';
    public string $sortDirection = 'asc';

    /**
     * Mengambil daftar mata pelajaran dari database untuk filter.
     */
    #[Computed]
    public function subjects()
    {
        return Subject::orderBy('name')->get();
    }

    /**
     * Mengambil data materi dengan filter, sorting, dan paginasi.
     */
    #[Computed]
    public function materials()
    {
        return Material::query()
            ->with(['subject', 'uploader'])
            ->where('user_id', Auth::id())
            ->when($this->search, fn($query) => $query->where('title', 'like', '%' . $this->search . '%'))
            ->when($this->filterSubject, fn($query) => $query->where('subject_id', $this->filterSubject))
            ->when($this->filterPublished !== '', fn($query) => $query->where('is_published', $this->filterPublished))
            ->orderBy($this->sortBy, $this->sortDirection) // Sorting berdasarkan kolom yang dipilih
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
        $this->resetPage(); // Reset paginasi setelah sorting
    }

    public function confirmDelete($materialId)
    {

        $material = Material::find($materialId);
        dd($material);

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

        session()->flash('flash-message', [
            'message' => 'Materi berhasil dihapus.',
            'type' => 'success'
        ]);

        $material->delete();


        $this->closeConfirmModal();
    }


    public function closeConfirmModal()
    {
        $this->confirmingDeletion = false;
        $this->materialToDelete = null;
    }

    public function download(int $materialId): ?StreamedResponse
    {
        $material = Material::find($materialId);

        // Pastikan materi ada dan pengguna berhak mengakses
        if (!$material || $material->user_id !== Auth::id() || !$material->file_path) {
            session()->flash('flash-message', [
                'message' => 'File tidak ditemukan atau Anda tidak memiliki izin untuk mengunduh.',
                'type' => 'error'
            ]);
            return null;
        }

        return Storage::disk('public')->download($material->file_path);
    }


    public function render()
    {
        return view('livewire.teacher.manage-materials');
    }
}