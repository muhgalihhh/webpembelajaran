<?php

namespace App\Livewire\Student;

use App\Models\Classes;
use App\Models\QuizAttempt;
use App\Models\Subject;
use App\Models\TaskSubmission;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\WithPagination;

#[Layout('layouts.landing')]
#[Title('Peringkat Siswa')]
class StudentRanking extends Component
{
    use WithPagination;

    public $perPage = 10;

    #[Url(as: 'mapel', history: true)]
    public $subjectFilter = '';

    #[Url(as: 'waktu', history: true)]
    public $timeFilter = '';

    public function updating($property)
    {
        if (in_array($property, ['subjectFilter', 'timeFilter'])) {
            $this->resetPage();
        }
    }

    #[Computed]
    public function rankings()
    {
        $loggedInStudent = auth()->user();

        if (!$loggedInStudent->class_id) {
            // Jika siswa tidak punya kelas, kembalikan paginator kosong
            return new LengthAwarePaginator([], 0, $this->perPage);
        }

        // 1. Tentukan batas waktu berdasarkan filter
        $timeBoundary = match ($this->timeFilter) {
            'minggu' => now()->subWeek(),
            '1_bulan' => now()->subMonth(),
            '3_bulan' => now()->subMonths(3),
            '6_bulan' => now()->subMonths(6),
            '1_tahun' => now()->subYear(),
            default => null,
        };

        // 2. Ambil semua siswa di kelas beserta nilai kuis dan tugas mereka dalam satu query
        $studentsWithScores = User::role('siswa')
            ->where('class_id', $loggedInStudent->class_id)
            ->with([
                'class:id,class',
                // Eager load quiz attempts dengan filter yang sudah diterapkan
                'quizAttempts' => function ($query) use ($timeBoundary) {
                    $query->select('user_id', 'score', 'quiz_id')
                        ->when($timeBoundary, fn($q) => $q->where('quiz_attempts.created_at', '>=', $timeBoundary))
                        ->when(
                            $this->subjectFilter,
                            fn($q) =>
                            $q->whereHas('quiz', fn($sq) => $sq->where('subject_id', $this->subjectFilter))
                        );
                },
                // Eager load task submissions dengan filter yang sudah diterapkan
                'taskSubmissions' => function ($query) use ($timeBoundary) {
                    $query->select('user_id', 'score', 'task_id')
                        ->where('status', 'graded')
                        ->when($timeBoundary, fn($q) => $q->where('task_submissions.created_at', '>=', $timeBoundary))
                        ->when(
                            $this->subjectFilter,
                            fn($q) =>
                            $q->whereHas('task', fn($sq) => $sq->where('subject_id', $this->subjectFilter))
                        );
                }
            ])
            ->get();

        // 3. Proses data nilai di PHP (lebih cepat daripada perulangan query)
        $studentRankings = $studentsWithScores->map(function ($student) {
            $quizScores = $student->quizAttempts->pluck('score');
            $taskScores = $student->taskSubmissions->pluck('score');

            $averageQuizScore = $quizScores->isNotEmpty() ? $quizScores->avg() : 0;
            $averageTaskScore = $taskScores->isNotEmpty() ? $taskScores->avg() : 0;

            $allScores = $quizScores->merge($taskScores);
            $overallAverage = $allScores->isNotEmpty() ? $allScores->avg() : 0;

            return [
                'id' => $student->id,
                'name' => $student->name,
                'class' => $student->class?->class ?? 'N/A',
                'quiz_score' => round($averageQuizScore, 2),
                'task_score' => round($averageTaskScore, 2),
                'overall_average' => round($overallAverage, 2),
            ];
        });

        // 4. Urutkan berdasarkan nilai rata-rata dan berikan peringkat (dengan penanganan nilai yang sama)
        $sortedRankings = $studentRankings->sortByDesc('overall_average')->values();

        $rank = 1;
        $previousScore = -1;
        $rankedStudents = $sortedRankings->map(function ($student, $key) use (&$rank, &$previousScore) {
            if ($student['overall_average'] !== $previousScore) {
                $rank = $key + 1;
            }
            $student['rank'] = $rank;
            $previousScore = $student['overall_average'];
            return $student;
        })->all();

        // 5. Lakukan paginasi secara manual pada data yang sudah diurutkan
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = array_slice($rankedStudents, ($currentPage - 1) * $this->perPage, $this->perPage);
        $paginatedItems = new LengthAwarePaginator($currentItems, count($rankedStudents), $this->perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath()
        ]);

        // Tambahkan nama mata pelajaran untuk ditampilkan di tabel
        $subjectName = $this->subjectFilter ? Subject::find($this->subjectFilter)->name : 'Semua Mata Pelajaran';
        $paginatedItems->getCollection()->transform(function ($student) use ($subjectName) {
            $student['subject_name'] = $subjectName;
            return $student;
        });

        return $paginatedItems;
    }

    #[Computed]
    public function subjects()
    {
        return Subject::query()
            ->whereHas('materials', function ($query) {
                $query->where('class_id', auth()->user()->class_id)
                    ->where('is_published', true)
                    ->where(fn($subQuery) => $subQuery->whereNull('published_at')->orWhere('published_at', '<=', now()));
            })
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function timeFilterOptions()
    {
        return [
            (object) ['id' => 'minggu', 'name' => '1 Minggu Terakhir'],
            (object) ['id' => '1_bulan', 'name' => '1 Bulan Terakhir'],
            (object) ['id' => '3_bulan', 'name' => '3 Bulan Terakhir'],
            (object) ['id' => '6_bulan', 'name' => '6 Bulan Terakhir'],
            (object) ['id' => '1_tahun', 'name' => '1 Tahun Terakhir'],
        ];
    }

    public function render()
    {
        return view('livewire.student.student-ranking');
    }
}