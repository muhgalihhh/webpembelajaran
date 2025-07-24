<?php

namespace App\Livewire\Teacher;

use App\Models\Material;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.teacher')]
class MaterialForm extends Component
{
    use WithFileUploads;

    public ?Material $material = null;

    // Properti Form
    #[Rule('required|string|max:255')]
    public $title;

    #[Rule('nullable|string')]
    public $description;

    #[Rule('nullable|url')]
    public $url;

    #[Rule('required|exists:subjects,id')]
    public $subject_id;

    #[Rule('required|boolean')]
    public $is_published = false;

    #[Rule('nullable|file|mimes:pdf,doc,docx,ppt,pptx,jpg,png|max:10240')]
    public $uploadedFile;

    public function mount(Material $material = null)
    {
        if ($material->exists) {
            $this->material = $material;
            $this->title = $material->title;
            $this->description = $material->description;
            $this->url = $material->url;
            $this->subject_id = $material->subject_id;
            $this->is_published = $material->is_published;
        }
    }

    public function getSubjectsProperty()
    {
        // Mengambil semua mata pelajaran yang tersedia
        return Subject::orderBy('name')->get();
    }

    public function save()
    {
        $validatedData = $this->validate();

        if ($this->uploadedFile) {
            // Hapus file lama jika ada saat update
            if ($this->material?->file_path) {
                Storage::disk('public')->delete($this->material->file_path);
            }
            $validatedData['file_path'] = $this->uploadedFile->store('materials', 'public');
        }

        unset($validatedData['uploadedFile']);

        if ($this->material) {
            $this->material->update($validatedData);
            $message = 'Materi berhasil diperbarui.';
        } else {
            // Tambahkan teacher_id dari user yang sedang login saat membuat materi baru
            $validatedData['teacher_id'] = Auth::id();
            Material::create($validatedData);
            $message = 'Materi berhasil ditambahkan.';
        }

        // Menggunakan session flash karena kita akan redirect
        session()->flash('success', $message);
        return $this->redirectRoute('teacher.materials', navigate: true);
    }

    #[Title('Form Materi')]
    public function render()
    {
        return view('livewire.teacher.material-form');
    }
}
