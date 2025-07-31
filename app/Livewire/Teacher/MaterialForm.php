<?php

namespace App\Livewire\Teacher;

use App\Models\Classes;
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
    public $class_id = '';
    public string $chapter = '';
    public bool $is_published = false;
    public ?string $url = '';
    public $uploadedFile;
    public $content = '';
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
            $this->content = $this->material->content;
            $this->subject_id = $this->material->subject_id;
            $this->class_id = $this->material->class_id;
            $this->chapter = $this->material->chapter ?? '';
            $this->is_published = $this->material->is_published;
            $this->url = $this->material->youtube_url;

            if ($this->material->file_path && Storage::disk('public')->exists($this->material->file_path)) {
                $this->currentFileUrl = Storage::url($this->material->file_path);
            }
        }
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

    /**
     * Menyimpan data (membuat baru atau update).
     */
    public function save()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:classes,id',
            'chapter' => 'nullable|string|max:100',
            'content' => 'nullable|string',
            'is_published' => 'required|boolean',
            'url' => 'nullable|url',
            'uploadedFile' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240',
        ];

        $this->validate($rules);

        try {
            $dataToSave = [
                'title' => $this->title,
                'description' => $this->description,
                'content' => $this->content,
                'subject_id' => $this->subject_id,
                'class_id' => $this->class_id,
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

                // --- PERBAIKAN UTAMA: Menggunakan cara yang benar untuk menyimpan file ---
                $path = $this->uploadedFile->storeAs('materi', $newFileName, 'public');

                $dataToSave['file_path'] = $path;
            }

            $this->material = Material::updateOrCreate(
                ['id' => $this->material->id],
                $dataToSave
            );

            $message = $this->material->wasRecentlyCreated ? 'Materi berhasil ditambahkan.' : 'Materi berhasil diperbarui.';

            session()->flash('flash-message', [
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