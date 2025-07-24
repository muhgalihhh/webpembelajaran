<?php

namespace App\Livewire\Teacher;

use App\Models\Material;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class ManageMaterials extends Component
{
    use WithPagination;

    public $search = '';
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        // Menampilkan SEMUA materi dari semua guru, dengan relasi yang benar
        $materials = Material::query()
            ->where('title', 'like', '%' . $this->search . '%')
            ->with('subject', 'uploader') // Menggunakan relasi 'uploader'
            ->latest()
            ->paginate(10);

        return view('livewire.teacher.manage-materials', [
            'materials' => $materials,
        ])->layout('layouts.teacher');
    }

    /**
     * Mengubah status publikasi materi.
     */
    public function togglePublish(Material $material)
    {
        // Otorisasi: Hanya pemilik yang bisa mengubah status
        if ($material->user_id !== Auth::id()) {
            session()->flash('error', 'Anda tidak diizinkan mengubah status materi ini.');
            return;
        }

        $material->is_published = !$material->is_published;
        $material->published_at = $material->is_published ? now() : null;
        $material->save();

        session()->flash('message', 'Status publikasi materi berhasil diubah.');
    }


    public function delete(Material $material)
    {

        if ($material->user_id !== Auth::id()) {
            session()->flash('error', 'Anda tidak diizinkan menghapus materi ini.');
            return;
        }

        if ($material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();
        session()->flash('message', 'Materi berhasil dihapus.');
    }
}