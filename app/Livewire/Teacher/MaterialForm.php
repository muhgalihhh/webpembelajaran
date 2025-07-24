<?php

namespace App\Livewire\Teacher;

use App\Models\Material;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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

    public ?Material $material;

    // Properti yang di-binding ke form
    public string $title = '';
    public string $description = '';
    public $subject_id = '';
    public string $chapter = '';
    public bool $is_published = false;
    public ?string $url = '';
    public $uploadedFile;

    // Properti baru untuk menampilkan file yang sudah ada di view
    public ?string $currentFileUrl = null;

    /**
     * Mount dijalankan saat komponen di-load.
     */
    public function mount(Material $material)
    {
        $this->material = $material;

        if ($this->material->exists) {
            $this->title = $this->material->title;
            $this->description = $this->material->description;
            $this->subject_id = $this->material->subject_id;
            $this->chapter = $this->material->chapter ?? '';
            $this->is_published = $this->material->is_published;
            $this->url = $this->material->youtube_url;

            // Jika ada file, buat URL-nya untuk ditampilkan di view
            if ($this->material->file_path && Storage::disk('public')->exists($this->material->file_path)) {
                $this->currentFileUrl = Storage::url($this->material->file_path);
            }
        }
    }

    /**
     * Mengambil daftar mata pelajaran.
     */
    #[Computed]
    public function subjects()
    {
        return Subject::orderBy('name')->get();
    }

    /**
     * Menyimpan data (membuat baru atau update).
     */
    public function save()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
            'chapter' => 'nullable|string|max:100',
            'is_published' => 'required|boolean',
            'url' => 'nullable|url',
            'uploadedFile' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240',
        ];

        $this->validate($rules);

        try {
            $dataToSave = [
                'title' => $this->title,
                'description' => $this->description,
                'subject_id' => $this->subject_id,
                'chapter' => $this->chapter,
                'is_published' => $this->is_published,
                'youtube_url' => $this->url,
                'user_id' => Auth::id(),
            ];

            if ($this->uploadedFile) {
                if ($this->material->exists && $this->material->file_path) {
                    Storage::disk('public')->delete($this->material->file_path);
                }

                $subject = Subject::find($this->subject_id);
                $subjectName = $subject ? $subject->name : 'mapel';
                $sanitizedChapter = Str::slug($this->chapter ?: 'bab', '_');
                $sanitizedSubjectName = Str::slug($subjectName, '_');
                $extension = $this->uploadedFile->getClientOriginalExtension();
                $newFileName = "{$sanitizedChapter}_{$sanitizedSubjectName}_" . Str::random(10) . ".{$extension}";

                $path = Storage::disk('public')->putFileAs(
                    'materi',
                    $this->uploadedFile,
                    $newFileName
                );

                $dataToSave['file_path'] = $path;
            }

            $this->material = Material::updateOrCreate(
                ['id' => $this->material->id],
                $dataToSave
            );

            $message = $this->material->wasRecentlyCreated ? 'Materi berhasil ditambahkan.' : 'Materi berhasil diperbarui.';

            // --- PERUBAHAN DARI SESSION KE DISPATCH ---
            $this->dispatch('flash-message', [
                'message' => $message,
                'type' => 'success'
            ]);


            $this->redirectRoute('teacher.materials');

        } catch (\Exception $e) {

            $this->dispatch('flash-message', [
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'type' => 'error'
            ]);

        }
    }

    public function render()
    {
        return view('livewire.teacher.material-form');
    }
}