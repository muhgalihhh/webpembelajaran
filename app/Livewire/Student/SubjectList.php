<?php

namespace App\Livewire\Student;

use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.landing')]
#[Title('Mata Pelajaran')]
class SubjectList extends Component
{
    // Properti baru untuk menampung filter yang dipilih
    public string $kurikulumFilter = '';

    /**
     * Mengambil daftar kurikulum yang unik berdasarkan materi yang tersedia
     * untuk kelas siswa. Ini akan digunakan untuk mengisi dropdown filter.
     */
    #[Computed]
    public function kurikulumOptions()
    {
        $studentClassId = Auth::user()->class_id;

        if (!$studentClassId) {
            return [];
        }

        // Ambil kurikulum yang unik dari mapel yang relevan
        return Subject::whereHas('materials', function ($query) use ($studentClassId) {
                $query->where('class_id', $studentClassId)
                    ->where('is_published', true)
                    ->where(fn($subQuery) => $subQuery->whereNull('published_at')->orWhere('published_at', '<=', now()));
            })
            ->distinct()
            ->pluck('kurikulum')
            ->filter() // Menghapus nilai null atau kosong dari hasil
            ->sort()
            ->values()
            ->all();
    }

    /**
     * Memperbarui query utama untuk memfilter mata pelajaran
     * berdasarkan properti $kurikulumFilter.
     */
    #[Computed]
    public function subjects()
    {
        $studentClassId = Auth::user()->class_id;

        if (!$studentClassId) {
            return collect();
        }

        return Subject::query() // Mulai dengan query builder
            ->whereHas('materials', function ($query) use ($studentClassId) {
                $query->where('class_id', $studentClassId)
                    ->where('is_published', true)
                    ->where(fn($subQuery) => $subQuery->whereNull('published_at')->orWhere('published_at', '<=', now()));
            })
            // Terapkan filter HANYA JIKA $this->kurikulumFilter memiliki nilai
            ->when($this->kurikulumFilter, function ($query) {
                $query->where('kurikulum', $this->kurikulumFilter);
            })
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        return view('livewire.student.subject-list');
    }
}
