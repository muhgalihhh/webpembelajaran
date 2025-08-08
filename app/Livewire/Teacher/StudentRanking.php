<?php

namespace App\Livewire\Teacher;

use App\Exports\CrudTableExport;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('layouts.teacher')]
#[Title('Peringkat Siswa')]
class StudentRanking extends Component
{
    #[Url(as: 'kelas')]
    public string $classFilter = '';

    // 1. Tambahkan filter kurikulum
    #[Url(as: 'kurikulum')]
    public string $kurikulumFilter = '';

    #[Url(as: 'urutkan')]
    public string $sortBy = 'average_score';

    #[Computed]
    public function studentsWithScores()
    {
        if (empty($this->classFilter) || empty($this->kurikulumFilter)) {
            return collect();
        }

        $students = User::role('siswa')
            ->where('class_id', $this->classFilter)
            ->with([
                'quizAttempts.quiz.subject',
                'taskSubmissions.task.subject'
            ])
            ->get();

        $subjects = $this->availableSubjects;

        $studentData = $students->map(function ($student) use ($subjects) {
            $scoresBySubject = [];
            $allScores = collect();

            foreach ($subjects as $subject) {
                $scoresBySubject[$subject->name] = null;
            }

            // Filter nilai berdasarkan kurikulum yang dipilih
            $quizScores = $student->quizAttempts->where('quiz.subject.kurikulum', $this->kurikulumFilter)->pluck('score', 'quiz.subject.name');
            $taskScores = $student->taskSubmissions->where('status', 'graded')->where('task.subject.kurikulum', $this->kurikulumFilter)->pluck('score', 'task.subject.name');

            foreach ($subjects as $subject) {
                $subjectScores = collect($quizScores->get($subject->name))->merge($taskScores->get($subject->name));
                if ($subjectScores->isNotEmpty()) {
                    $scoresBySubject[$subject->name] = round($subjectScores->avg(), 2);
                    $allScores = $allScores->merge($subjectScores);
                }
            }

            return [
                'name' => $student->name,
                'scores' => $scoresBySubject,
                'average_score' => $allScores->isNotEmpty() ? round($allScores->avg(), 2) : 0,
            ];
        });

        if ($this->sortBy === 'student_name') {
            return $studentData->sortBy('name')->values();
        }

        return $studentData->sortByDesc('average_score')->values();
    }

    #[Computed]
    public function availableSubjects()
    {
        // 2. Filter mata pelajaran berdasarkan kurikulum yang dipilih
        return Subject::when($this->kurikulumFilter, fn($q) => $q->where('kurikulum', $this->kurikulumFilter))
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function availableClasses()
    {
        return Classes::orderBy('class')->get();
    }

    // 3. Tambahkan computed property untuk opsi filter kurikulum
    #[Computed]
    public function kurikulumOptions()
    {
        return Subject::select('kurikulum')->distinct()->pluck('kurikulum', 'kurikulum');
    }

    public function exportExcel()
    {
        $students = $this->studentsWithScores;
        $subjects = $this->availableSubjects;

        if ($students->isEmpty()) {
            $this->dispatch('flash-message', ['message' => 'Tidak ada data untuk diekspor. Silakan pilih kelas dan kurikulum terlebih dahulu.', 'type' => 'warning']);
            return;
        }

        $headings = ['Nama Siswa'];
        foreach ($subjects as $subject) {
            $headings[] = $subject->name;
        }
        $headings[] = 'Rata-Rata';

        $exportData = $students->map(function ($student) use ($subjects) {
            $row = ['name' => $student['name']];
            foreach ($subjects as $subject) {
                $row[$subject->name] = $student['scores'][$subject->name] ?? '-';
            }
            $row['average_score'] = $student['average_score'];
            return $row;
        });

        $className = Classes::find($this->classFilter)->class;
        $fileName = "Daftar Nilai - Kelas {$className} ({$this->kurikulumFilter}) - " . now()->format('d-m-Y') . '.xlsx';

        return Excel::download(new CrudTableExport($exportData, $headings), $fileName);
    }

    public function render()
    {
        return view('livewire.teacher.student-ranking');
    }
}