<?php

namespace App\Livewire\Teacher;

use App\Models\Classes;
use App\Models\Material;
use App\Models\Subject;
use App\Notifications\NotificationStudent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
    public string $content = '';
    public ?string $currentFileUrl = null;
    public ?int $page_count = null;

    // Track status untuk notifikasi
    private bool $originalPublishStatus = false;

    protected $rules = [
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

    protected $messages = [
        'title.required' => 'Judul materi wajib diisi.',
        'description.required' => 'Deskripsi materi wajib diisi.',
        'subject_id.required' => 'Mata pelajaran wajib dipilih.',
        'class_id.required' => 'Kelas wajib dipilih.',
        'uploadedFile.mimes' => 'File harus berformat PDF, DOC, DOCX, PPT, PPTX, atau ZIP.',
        'uploadedFile.max' => 'Ukuran file maksimal 10MB.',
        'url.url' => 'Format URL tidak valid.',
    ];

    public function mount(Material $material)
    {
        $this->material = $material;

        if ($this->material->exists) {
            $this->fill($this->material->toArray());
            $this->url = $this->material->youtube_url;
            $this->originalPublishStatus = $this->material->is_published;

            if ($this->material->file_path && Storage::disk('public')->exists($this->material->file_path)) {
                $this->currentFileUrl = Storage::url($this->material->file_path);
            }
        }
    }

    public function updatedUploadedFile($file)
    {
        $this->validateOnly('uploadedFile');

        $this->page_count = null;
        if ($file && $file->getClientOriginalExtension() === 'pdf') {
            try {
                $this->page_count = (new Pdf())->setPdf($file->getRealPath())->getNumberOfPages();
            } catch (\Exception $e) {
                $this->addError('uploadedFile', 'Gagal memproses file PDF. Pastikan file tidak rusak atau terproteksi.');
                $this->reset('uploadedFile');
            }
        }
    }

    #[Computed]
    public function subjects()
    {
        return Subject::orderBy('kurikulum', 'asc')->orderBy('name')->get()
            ->mapWithKeys(function ($subject) {
                $displayText = "{$subject->name} - ({$subject->kurikulum})";
                return [$subject->id => $displayText];
            });
    }

    #[Computed]
    public function classes()
    {
        return Classes::orderBy('class')->get()->pluck('class', 'id');
    }

    public function save()
    {
        $this->validate();

        try {
            $dataToSave = [
                'title' => trim($this->title),
                'description' => trim($this->description),
                'content' => $this->content,
                'subject_id' => $this->subject_id,
                'class_id' => $this->class_id,
                'chapter' => $this->chapter ? trim($this->chapter) : null,
                'is_published' => $this->is_published,
                'youtube_url' => $this->url ? trim($this->url) : null,
                'user_id' => Auth::id(),
                'page_count' => $this->page_count,
            ];

            // Handle file upload
            if ($this->uploadedFile) {
                // Hapus file lama jika ada
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

            // Save material
            $material = Material::updateOrCreate(['id' => $this->material->id], $dataToSave);

            // Tentukan status untuk notifikasi
            $wasRecentlyCreated = $material->wasRecentlyCreated;
            $statusChangedToPublished = !$this->originalPublishStatus && $this->is_published;

            // Tentukan pesan sukses
            $message = $wasRecentlyCreated ? 'Materi berhasil ditambahkan.' : 'Materi berhasil diperbarui.';

            // Kirim notifikasi jika materi dipublish (baru dibuat atau status berubah ke published)
            if ($material->is_published && ($wasRecentlyCreated || $statusChangedToPublished)) {
                $this->sendNotificationToStudents($material);
                $message .= ' Notifikasi telah dikirim ke siswa.';
            }

            // Set flash message
            session()->flash('flash_message', [
                'message' => $message,
                'type' => 'success'
            ]);

            return $this->redirectRoute('teacher.materials', navigate: true);

        } catch (\Exception $e) {
            Log::error('Error saving material: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'material_id' => $this->material->id ?? null,
                'trace' => $e->getTraceAsString()
            ]);

            session()->flash('flash_message', [
                'message' => 'Terjadi kesalahan saat menyimpan materi. Silakan coba lagi.',
                'type' => 'error'
            ]);
        }
    }

    private function sendNotificationToStudents(Material $material)
    {
        try {
            if ($class = Classes::find($material->class_id)) {
                $students = $class->users()
                    ->whereHas('roles', fn($q) => $q->where('name', 'siswa'))
                    ->get();

                if ($students->isNotEmpty()) {
                    Notification::send($students, new NotificationStudent($material));

                    Log::info('Notification sent to students', [
                        'material_id' => $material->id,
                        'material_title' => $material->title,
                        'class_id' => $material->class_id,
                        'student_count' => $students->count(),
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to send notification: ' . $e->getMessage(), [
                'material_id' => $material->id,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.teacher.material-form');
    }
}
