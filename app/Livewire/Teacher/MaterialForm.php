<?php

namespace App\Livewire\Teacher;

use App\Models\Classes;
use App\Models\Material;
use App\Models\Subject;
use App\Notifications\NotificationStudent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\PdfToText\Pdf;

#[Layout('layouts.teacher')]
#[Title('Form Materi')]
class MaterialForm extends Component
{
    use WithFileUploads;

    public ?Material $material;

    public string $title = '';
    public string $description = '';
    public $subject_id = '';
    public $class_id = '';
    public string $chapter = '';
    public bool $is_published = false;
    public ?string $url = null;
    public $uploadedFile;
    public $content = '';
    public ?string $currentFileUrl = null;
    public ?int $page_count = null;

    public function mount(Material $material)
    {
        $this->material = $material;

        if ($this->material->exists) {
            $this->fill($this->material->toArray());
            $this->url = $this->material->youtube_url; // Penyesuaian nama kolom

            if ($this->material->file_path && Storage::disk('public')->exists($this->material->file_path)) {
                $this->currentFileUrl = Storage::url($this->material->file_path);
            }
        }
    }

    public function updatedUploadedFile($file)
    {
        $this->validateOnly('uploadedFile', [
            'uploadedFile' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $this->page_count = null;
        if ($file && $file->getClientOriginalExtension() === 'pdf') {
            try {
                $this->page_count = (new Pdf())->setPdf($file->getRealPath())->getNumberOfPages();
            } catch (\Exception $e) {
                $this->addError('uploadedFile', 'Gagal memproses file PDF. File mungkin rusak.');
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

        $validated = $this->validate($rules);

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
                'page_count' => $this->page_count,
            ];

            if ($this->uploadedFile) {
                if ($this->material->exists && $this->material->file_path) {
                    Storage::disk('public')->delete($this->material->file_path);
                }

                $subject = Subject::find($this->subject_id);
                $subjectName = Str::slug($subject?->name ?: 'mapel', '_');
                $chapterName = Str::slug($this->chapter ?: 'bab', '_');
                $extension = $this->uploadedFile->getClientOriginalExtension();
                $fileName = "{$chapterName}_{$subjectName}_" . Str::random(10) . ".{$extension}";

                $dataToSave['file_path'] = $this->uploadedFile->storeAs('materi', $fileName, 'public');
            }

            $material = Material::updateOrCreate(['id' => $this->material->id], $dataToSave);

            $wasRecentlyCreated = $material->wasRecentlyCreated;
            $message = $wasRecentlyCreated ? 'Materi berhasil ditambahkan.' : 'Materi berhasil diperbarui.';

            if ($wasRecentlyCreated && $material->is_published) {
                if ($class = Classes::find($material->class_id)) {
                    $students = $class->users()->whereHas('roles', fn($q) => $q->where('name', 'siswa'))->get();
                    if ($students->isNotEmpty()) {
                        Notification::send($students, new NotificationStudent($material));
                    }
                }
            }

            session()->flash('flash_message', ['message' => $message, 'type' => 'success']);
            $this->redirect(route('teacher.materials'), navigate: true);

        } catch (\Exception $e) {
            session()->flash('flash_message', ['message' => 'Terjadi kesalahan: ' . $e->getMessage(), 'type' => 'error']);
        }
    }

    public function render()
    {
        return view('livewire.teacher.material-form');
    }
}
