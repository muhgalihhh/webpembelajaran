<?php

namespace App\Livewire\Teacher;

use App\Models\Material;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;


#[Layout("layouts.teacher")]
class MaterialForm extends Component
{
    use WithFileUploads;

    // Properti Model
    public Material $material;

    // Properti Form
    public $title, $subject_id, $chapter, $content, $youtube_url;
    public $file;
    public $is_published = false; // Defaultnya tidak dipublikasi

    public $existingFilePath;
    public $isEditing = false;

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'chapter' => 'nullable|string|max:100',
            'content' => 'required|string|min:20',
            'youtube_url' => 'nullable|url',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip,rar|max:20480', // 20MB Max
            'is_published' => 'boolean',
        ];
    }

    public function mount(Material $material)
    {
        if ($material->exists) {
            // Otorisasi: Pastikan hanya pemilik yang bisa masuk halaman edit
            if ($material->user_id !== Auth::id()) {
                abort(403, 'AKSES DITOLAK');
            }
            $this->material = $material;
            $this->isEditing = true;

            // Isi properti form dari data model
            $this->title = $material->title;
            $this->subject_id = $material->subject_id;
            $this->chapter = $material->chapter;
            $this->content = $material->content;
            $this->youtube_url = $material->youtube_url;
            $this->is_published = $material->is_published;
            $this->existingFilePath = $material->file_path;
        } else {
            // Mode buat baru
            $this->material = new Material();
        }
    }

    public function save()
    {
        $this->validate();

        // Tetapkan user_id sebagai ID user yang sedang login
        $this->material->user_id = Auth::id();

        // Map properti form ke model
        $this->material->title = $this->title;
        $this->material->subject_id = $this->subject_id;
        $this->material->chapter = $this->chapter;
        $this->material->content = $this->content;
        $this->material->youtube_url = $this->youtube_url;
        $this->material->is_published = $this->is_published;

        // Atur tanggal publikasi jika dipublikasikan
        if ($this->is_published && !$this->material->published_at) {
            $this->material->published_at = now();
        } elseif (!$this->is_published) {
            $this->material->published_at = null;
        }

        // Handle upload file
        if ($this->file) {
            if ($this->material->file_path) {
                Storage::disk('public')->delete($this->material->file_path);
            }
            $this->material->file_path = $this->file->store('materials', 'public');
        }

        $this->material->save();

        session()->flash('message', 'Materi berhasil ' . ($this->isEditing ? 'diperbarui' : 'disimpan') . '.');
        return $this->redirectRoute('teacher.materials');
    }

    public function render()
    {
        $subjects = Subject::all();

        return view('livewire.teacher.material-form', [
            'subjects' => $subjects,
        ]);
    }
}