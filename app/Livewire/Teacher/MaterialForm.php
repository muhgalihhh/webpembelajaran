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

            if ($this->material->file_path && Storage::disk('private')->exists($this->material->file_path)) {
                $this->currentFileUrl = $this->material->file_path;
            }
        }
    }

    public function updatedUploadedFile($file)
    {
        $this->validateOnly('uploadedFile');

        // Reset page count
        $this->page_count = null;

        if ($file && strtolower($file->getClientOriginalExtension()) === 'pdf') {
            $this->countPdfPages($file);
        }
    }

    private function countPdfPages($file)
    {
        try {
            // Method 1: Pure PHP - Manual PDF parsing (TANPA dependencies)
            $content = file_get_contents($file->getRealPath());
            if ($content !== false) {
                // Cari pattern /Type /Page untuk menghitung halaman
                $pageCount = preg_match_all('/\/Type\s*\/Page[^s]/i', $content);
                if ($pageCount > 0) {
                    $this->page_count = $pageCount;
                    return;
                }

                // Alternative: cari pattern /Count
                if (preg_match('/\/Count\s+(\d+)/', $content, $matches)) {
                    $this->page_count = (int) $matches[1];
                    return;
                }

                // Alternative: cari /N (number of pages)
                if (preg_match('/\/N\s+(\d+)/', $content, $matches)) {
                    $this->page_count = (int) $matches[1];
                    return;
                }
            }
        } catch (\Exception $e) {
            Log::warning('Manual PDF parsing failed: ' . $e->getMessage());
        }

        try {
            // Method 2: Menggunakan Spatie PdfToText (jika tersedia)
            if (class_exists('\Spatie\PdfToText\Pdf')) {
                $pdf = new Pdf();
                $this->page_count = $pdf->setPdf($file->getRealPath())->getNumberOfPages();
                return;
            }
        } catch (\Exception $e) {
            Log::warning('Spatie PDF method failed: ' . $e->getMessage());
        }

        try {
            // Method 3: Menggunakan imagick jika tersedia
            if (extension_loaded('imagick')) {
                $imagick = new \Imagick();
                $imagick->readImage($file->getRealPath());
                $this->page_count = $imagick->getNumberImages();
                $imagick->clear();
                $imagick->destroy();
                return;
            }
        } catch (\Exception $e) {
            Log::warning('Imagick method failed: ' . $e->getMessage());
        }

        // Jika semua method gagal, biarkan null (tidak error)
        Log::warning('All PDF page counting methods failed for file: ' . $file->getClientOriginalName());
        $this->page_count = null; // Tidak menampilkan error ke user
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
                    Storage::disk('private')->delete($this->material->file_path);
                }

                $subject = Subject::find($this->subject_id);
                $subjectName = Str::slug($subject?->name ?: 'mapel', '_');
                $chapterName = Str::slug($this->chapter ?: 'bab', '_');
                $extension = $this->uploadedFile->getClientOriginalExtension();
                $fileName = "{$chapterName}_{$subjectName}_" . Str::random(10) . ".{$extension}";

                $dataToSave['file_path'] = $this->uploadedFile->storeAs('materi', $fileName, 'private');

                // Jika masih belum ada page_count dan ini PDF, coba hitung lagi setelah file tersimpan
                if (!$this->page_count && strtolower($extension) === 'pdf') {
                    $this->countPdfPagesFromStorage($dataToSave['file_path']);
                    $dataToSave['page_count'] = $this->page_count;
                }
            }

            // Simpan material
            $isNewRecord = !$this->material->exists;
            $material = Material::updateOrCreate(['id' => $this->material->id], $dataToSave);
            $material->refresh();

            // === LOGIKA NOTIFIKASI BARU ===
            $isNowPublished = $this->is_published;
            $wasPreviouslyPublished = $this->originalPublishStatus;
            $notificationType = null;
            $message = $isNewRecord ? 'Materi berhasil ditambahkan.' : 'Materi berhasil diperbarui.';

            if ($isNowPublished) {
                // Kasus 1: Materi dipublikasikan untuk PERTAMA KALI (sebelumnya draft atau baru dibuat).
                if (!$wasPreviouslyPublished) {
                    $notificationType = 'new';
                    $message = 'Materi berhasil dipublikasikan. Notifikasi materi baru telah dikirim ke siswa.';
                }
                // Kasus 2: Materi yang SUDAH PUBLISH diperbarui.
                elseif (!$isNewRecord && $wasPreviouslyPublished) {
                    $notificationType = 'updated';
                    $message = 'Materi berhasil diperbarui. Notifikasi pembaruan telah dikirim ke siswa.';
                }
            }

            // Kirim notifikasi jika tipe notifikasi sudah ditentukan
            if ($notificationType) {
                $this->sendNotificationToStudents($material, $notificationType);
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

    private function countPdfPagesFromStorage($filePath)
    {
        try {
            $fullPath = Storage::disk('private')->path($filePath);
            if (file_exists($fullPath)) {
                // Gunakan method yang sama seperti sebelumnya
                $tempFile = new \stdClass();
                $tempFile->path = $fullPath;

                // Method dengan Spatie
                if (class_exists('\Spatie\PdfToText\Pdf')) {
                    $pdf = new Pdf();
                    $this->page_count = $pdf->setPdf($fullPath)->getNumberOfPages();
                    return;
                }

                // Method lainnya bisa ditambahkan di sini jika diperlukan
            }
        } catch (\Exception $e) {
            Log::warning('Failed to count pages from storage: ' . $e->getMessage());
        }
    }

    private function sendNotificationToStudents(Material $material, string $notificationType)
    {
        try {
            if ($class = Classes::find($material->class_id)) {
                $students = $class->users()
                    ->whereHas('roles', fn($q) => $q->where('name', 'siswa'))
                    ->get();

                if ($students->isNotEmpty()) {
                    // Kirim tipe notifikasi ke constructor
                    Notification::send($students, new NotificationStudent($material, $notificationType));
                    Log::info('Notification sent to students', [
                        'material_id' => $material->id,
                        'notification_type' => $notificationType,
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
