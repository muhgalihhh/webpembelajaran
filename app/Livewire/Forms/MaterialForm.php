<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Rule;
use Livewire\Form;

class MaterialForm extends Form
{
    #[Rule('required|exists:courses,id', as: 'Mata Kuliah')]
    public $course_id = '';

    #[Rule('required|string|min:5', as: 'Judul')]
    public $title = '';

    #[Rule('required|string|min:10', as: 'Deskripsi')]
    public $description = '';

    // Validasi untuk file: opsional, harus file, maks 10MB
    #[Rule('nullable|file|max:10240', as: 'File Materi')]
    public $file_path;
}