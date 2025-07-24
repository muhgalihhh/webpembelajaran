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
use Livewire\WithFileUploads;

#[Layout('layouts.teacher')]
#[Title('Form Materi')]
class MaterialForm extends Component
{
    use WithFileUploads;

    // Properti untuk menampung model Material saat edit
    public ?Material $material;

    // Properti yang di-binding ke form
    public string $title = '';
    public string $description = '';
    public string $content = ''; // Add content field for rich text
    public string $chapter = ''; // Add chapter field
    public $subject_id = '';
    public bool $is_published = false;
    public ?string $url = ''; // Untuk youtube_url atau link eksternal lainnya
    public $uploadedFile; // Untuk menampung file yang diupload

    /**
     * Metode 'mount' ini berfungsi seperti constructor.
     * Akan dijalankan saat komponen pertama kali di-load.
     * Ini akan mengisi form jika dalam mode edit.
     */
    public function mount(Material $material)
    {
        // SELALU inisialisasi properti $material di awal.
        // Untuk mode 'create', $material akan menjadi objek Material kosong.
        // Untuk mode 'edit', $material akan berisi data dari database.
        $this->material = $material;

        // Jika $material berisi data (mode edit), isi form.
        if ($this->material->exists) {
            $this->title = $this->material->title;
            $this->description = $this->material->description;
            $this->content = $this->material->content ?? '';
            $this->chapter = $this->material->chapter ?? '';
            $this->subject_id = $this->material->subject_id;
            $this->is_published = $this->material->is_published;
            $this->url = $this->material->youtube_url;
        }
    }

    /**
     * Mengambil daftar mata pelajaran dari database.
     * #[Computed] akan melakukan caching hasil query per request.
     */
    #[Computed]
    public function subjects()
    {
        return Subject::orderBy('name')->get();
    }

    /**
     * Update content from TinyMCE
     */
    public function updateContent($content)
    {
        $this->content = $content;
    }

    /**
     * Menyimpan data (baik membuat baru atau update)
     */
    public function save()
    {
        // Aturan validasi
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'nullable|string',
            'chapter' => 'nullable|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'is_published' => 'required|boolean',
            'url' => 'nullable|url',
            'uploadedFile' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240', // Maks 10MB
        ];

        $validatedData = $this->validate($rules);

        // Menyiapkan data untuk disimpan ke database
        $dataToSave = [
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->content,
            'chapter' => $this->chapter,
            'subject_id' => $this->subject_id,
            'is_published' => $this->is_published,
            'youtube_url' => $this->url,
            'user_id' => Auth::id(), // Mengambil ID guru yang login
            'published_at' => $this->is_published ? now() : null,
        ];

        // Proses upload file jika ada
        if ($this->uploadedFile) {
            // Hapus file lama jika ada (dalam mode edit)
            if ($this->material && $this->material->file_path) {
                Storage::disk('public')->delete($this->material->file_path);
            }
            // Simpan file baru
            $dataToSave['file_path'] = $this->uploadedFile->store('materials', 'public');
        }

        // Cek apakah mode edit atau tambah baru
        if ($this->material && $this->material->exists) {
            // Mode Update
            $this->material->update($dataToSave);
            $message = 'Materi berhasil diperbarui.';
        } else {
            // Mode Create
            Material::create($dataToSave);
            $message = 'Materi berhasil ditambahkan.';
        }

        session()->flash('flash-message', [
            'message' => $message,
            'type' => 'success'
        ]);

        return $this->redirect(route('teacher.materials'), navigate: true);
    }

    /**
     * Merender view komponen.
     */
    public function render()
    {
        return view('livewire.teacher.material-form');
    }
}