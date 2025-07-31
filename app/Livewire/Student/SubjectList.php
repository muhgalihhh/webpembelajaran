<?php

namespace App\Livewire\Student;

use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.landing')] // Menggunakan layout siswa
#[Title('Mata Pelajaran')]
class SubjectList extends Component
{
    /**
     * Mengambil daftar mata pelajaran yang memiliki materi
     * yang sudah dipublikasikan untuk kelas siswa saat ini.
     */
    #[Computed]
    public function subjects()
    {
        $studentClassId = Auth::user()->class_id;

        // Jika siswa tidak memiliki kelas, kembalikan koleksi kosong
        if (!$studentClassId) {
            return collect();
        }

        return Subject::whereHas('materials', function ($query) use ($studentClassId) {
            $query->where('class_id', $studentClassId)
                ->where('is_published', true)
                ->where(function ($subQuery) {
                    $subQuery->whereNull('published_at')
                        ->orWhere('published_at', '<=', now());
                });
        })->orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.student.subject-list');
    }
}
