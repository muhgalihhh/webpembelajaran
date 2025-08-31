<?php

namespace App\Livewire\Teacher;

use App\Models\Classes;
use App\Models\QuizAttempt;
use App\Models\Subject;
use App\Models\TaskSubmission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.teacher')]
#[Title('Dashboard Guru')]
class Dashboard extends Component
{
    use WithPagination;

    // Filter Utama
    #[Url(as: 'aktivitas', history: true)]
    public $activityType = 'quiz'; // 'quiz' atau 'tugas'

    // Filter lainnya
    #[Url(as: 'kelas', history: true)]
    public $classFilter = '';
    #[Url(as: 'mapel', history: true)]
    public $subjectFilter = '';
    #[Url(as: 'q', history: true)]
    public $searchQuery = '';
    #[Url(history: true)]
    public $sortBy = 'created_at';
    #[Url(history: true)]
    public $sortDirection = 'desc';

    public ?int $itemToDeleteId = null;

    public function setActivityType($type)
    {
        $this->activityType = $type;
        $this->resetPage();
    }

    public function updating($property)
    {
        if (in_array($property, ['classFilter', 'subjectFilter', 'searchQuery'])) {
            $this->resetPage();
        }
    }

    #[Computed]
    public function results()
    {
        if ($this->activityType === 'quiz') {
            $query = QuizAttempt::query()->with(['user.class', 'quiz.subject']);
            $query->when($this->searchQuery, fn($q) => $q->whereHas('quiz', fn($sq) => $sq->where('title', 'like', '%' . $this->searchQuery . '%')));
            $query->when($this->classFilter, fn($q) => $q->whereHas('user', fn($sq) => $sq->where('class_id', $this->classFilter)));
            $query->when($this->subjectFilter, fn($q) => $q->whereHas('quiz', fn($sq) => $sq->where('subject_id', $this->subjectFilter)));
        } else {
            $query = TaskSubmission::query()->with(['student.class', 'task.subject']);
            $query->when($this->searchQuery, fn($q) => $q->whereHas('task', fn($sq) => $sq->where('title', 'like', '%' . $this->searchQuery . '%')));
            $query->when($this->classFilter, fn($q) => $q->whereHas('student', fn($sq) => $sq->where('class_id', $this->classFilter)));
            $query->when($this->subjectFilter, fn($q) => $q->whereHas('task', fn($sq) => $sq->where('subject_id', $this->subjectFilter)));
        }

        $query->orderBy($this->sortBy, $this->sortDirection);
        return $query->paginate(10);
    }

    #[Computed]
    public function filterOptions()
    {
        return [
            'subjects' => Subject::orderBy('kurikulum', 'asc')->orderBy('name')->get()
                ->mapWithKeys(function ($subject) {
                    $displayText = "{$subject->name} - ({$subject->kurikulum})";
                    return [$subject->id => $displayText];
                }),
            'classes' => Classes::orderBy('class')->get(),
        ];
    }

    #[Computed]
    public function stats()
    {
        // Statistik untuk Quiz
        $quizStats = $this->getQuizStats();
        
        // Statistik untuk Tugas
        $taskStats = $this->getTaskStats();

        // Gabungkan statistik berdasarkan activity type yang dipilih
        if ($this->activityType === 'quiz') {
            return [
                'activeStudents' => $quizStats['activeStudents'],
                'totalAttempts' => $quizStats['totalAttempts'],
                'averageScore' => $quizStats['averageScore'],
                'topScore' => $quizStats['topScore'],
                'scoreDistribution' => $quizStats['scoreDistribution'],
                'todayCount' => $quizStats['todayCount'],
                'weeklyTrend' => $quizStats['weeklyTrend'],
            ];
        } else {
            return [
                'activeStudents' => $taskStats['activeStudents'],
                'totalAttempts' => $taskStats['totalAttempts'],
                'averageScore' => $taskStats['averageScore'],
                'topScore' => $taskStats['topScore'],
                'scoreDistribution' => $taskStats['scoreDistribution'],
                'todayCount' => $taskStats['todayCount'],
                'weeklyTrend' => $taskStats['weeklyTrend'],
            ];
        }
    }

    private function getQuizStats()
{
    $query = QuizAttempt::query();

    $query->when($this->classFilter, fn($q) => 
        $q->whereHas('user', fn($sq) => $sq->where('class_id', $this->classFilter))
    );
    $query->when($this->subjectFilter, fn($q) => 
        $q->whereHas('quiz', fn($sq) => $sq->where('subject_id', $this->subjectFilter))
    );

    $totalAttempts = (clone $query)->count();
    $activeStudents = (clone $query)->distinct('user_id')->count('user_id');

    $averageScore = $totalAttempts > 0 
        ? round((clone $query)->avg('score') ?? 0, 1) 
        : 0;

    $topScore = $totalAttempts > 0 
        ? (clone $query)->max('score') ?? 0 
        : 0;

    $todayCount = (clone $query)->whereDate('created_at', today())->count();
    $scoreDistribution = $this->calculateScoreDistribution((clone $query));
    $weeklyTrend = $this->calculateWeeklyTrend((clone $query));

    return compact(
        'activeStudents', 
        'totalAttempts', 
        'averageScore', 
        'topScore', 
        'scoreDistribution', 
        'todayCount', 
        'weeklyTrend'
    );
}

  private function getTaskStats()
{
    $query = TaskSubmission::query();

    // filter kelas
    $query->when($this->classFilter, fn($q) =>
        $q->whereHas('user', fn($sq) => $sq->where('class_id', $this->classFilter))
    );

    // filter mapel
    $query->when($this->subjectFilter, fn($q) =>
        $q->whereHas('task', fn($sq) => $sq->where('subject_id', $this->subjectFilter))
    );

    // total submission
    $totalAttempts = (clone $query)->count();

    // siswa aktif (unik user_id)
    $activeStudents = (clone $query)->distinct('user_id')->count('user_id');

    // rata-rata nilai
    $averageScore = $totalAttempts > 0
        ? round((clone $query)->avg('score') ?? 0, 1)
        : 0;

    // skor tertinggi
    $topScore = $totalAttempts > 0
        ? (clone $query)->max('score') ?? 0
        : 0;

    // submission hari ini → pakai submission_date
    $todayCount = (clone $query)->whereDate('submission_date', today())->count();

    // distribusi nilai
    $scoreDistribution = $this->calculateScoreDistribution((clone $query));

    // tren mingguan → pakai submission_date
    $weeklyTrend = $this->calculateWeeklyTrendForTasks((clone $query));

    return compact(
        'activeStudents',
        'totalAttempts',
        'averageScore',
        'topScore',
        'scoreDistribution',
        'todayCount',
        'weeklyTrend'
    );
}


    private function calculateScoreDistribution($query)
    {
        $totalCount = $query->count();
        
        if ($totalCount === 0) {
            return [
                'poor' => 0,      // 0-60
                'average' => 0,   // 61-80
                'good' => 0,      // 81-100
            ];
        }

        $poorCount = (clone $query)->where('score', '<', 61)->count();
        $averageCount = (clone $query)->whereBetween('score', [61, 80])->count();
        $goodCount = (clone $query)->where('score', '>', 80)->count();

        return [
            'poor' => round(($poorCount / $totalCount) * 100, 1),
            'average' => round(($averageCount / $totalCount) * 100, 1),
            'good' => round(($goodCount / $totalCount) * 100, 1),
        ];
    }

    private function calculateWeeklyTrend($query)
    {
        $weekData = [];
        $totalThisWeek = 0;
        $totalLastWeek = 0;

        // Data 7 hari terakhir
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $count = (clone $query)->whereDate('created_at', $date)->count();
            $weekData[] = $count;
            $totalThisWeek += $count;
        }

        // Data 7 hari sebelumnya untuk perbandingan
        for ($i = 13; $i >= 7; $i--) {
            $date = today()->subDays($i);
            $count = (clone $query)->whereDate('created_at', $date)->count();
            $totalLastWeek += $count;
        }

        // Hitung persentase perubahan
        $trendPercentage = 0;
        if ($totalLastWeek > 0) {
            $trendPercentage = round((($totalThisWeek - $totalLastWeek) / $totalLastWeek) * 100, 1);
        } elseif ($totalThisWeek > 0) {
            $trendPercentage = 100; // Jika minggu lalu 0 tapi minggu ini ada
        }

        return [
            'data' => $weekData,
            'percentage' => $trendPercentage,
            'direction' => $trendPercentage >= 0 ? 'up' : 'down'
        ];
    }

    private function calculateWeeklyTrendForTasks($query)
    {
        $weekData = [];
        $totalThisWeek = 0;
        $totalLastWeek = 0;

        // Data 7 hari terakhir
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $count = (clone $query)->whereDate('submission_date', $date)->count();
            $weekData[] = $count;
            $totalThisWeek += $count;
        }

        // Data 7 hari sebelumnya untuk perbandingan
        for ($i = 13; $i >= 7; $i--) {
            $date = today()->subDays($i);
            $count = (clone $query)->whereDate('submission_date', $date)->count();
            $totalLastWeek += $count;
        }

        // Hitung persentase perubahan
        $trendPercentage = 0;
        if ($totalLastWeek > 0) {
            $trendPercentage = round((($totalThisWeek - $totalLastWeek) / $totalLastWeek) * 100, 1);
        } elseif ($totalThisWeek > 0) {
            $trendPercentage = 100;
        }

        return [
            'data' => $weekData,
            'percentage' => $trendPercentage,
            'direction' => $trendPercentage >= 0 ? 'up' : 'down'
        ];
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortBy = $field;
    }

    public function render()
    {
        return view('livewire.teacher.dashboard');
    }

    public function prepareToDelete(int $id)
    {
        $this->itemToDeleteId = $id;
        $this->dispatch('open-confirm-modal');
    }

    public function delete()
    {
        if ($this->itemToDeleteId) {
            if ($this->activityType === 'quiz') {
                \App\Models\QuizAttempt::find($this->itemToDeleteId)?->delete();
            } else {
                \App\Models\TaskSubmission::find($this->itemToDeleteId)?->delete();
            }

            // Reset ID dan tampilkan pesan sukses
            $this->itemToDeleteId = null;
            session()->flash('flash-message', ['message' => 'Data berhasil dihapus.', 'type' => 'success']);
            $this->dispatch('flash-message', ['message' => 'Data berhasil dihapus.', 'type' => 'success']);
        }

        // Tutup modal setelah selesai
        $this->dispatch('close-confirm-modal');
    }
}