<?php

namespace App\Livewire\Teacher;

use App\Models\Classes;
use App\Models\Quiz;
use App\Models\Subject;
use App\Notifications\NotificationStudent; // <-- [1] Tambahkan Notifikasi
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification; // <-- [2] Tambahkan Notifikasi
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.teacher')]
#[Title('Manajemen Kuis')]
class ManageQuizzes extends Component
{
    use WithPagination;

    // Properti Filter
    #[Url(as: 'q', keep: true)] // <-- [3] Tambahkan 'keep: true' agar filter tidak hilang saat pindah halaman
    public string $search = '';
    #[Url(as: 'mapel', keep: true)]
    public string $subjectFilter = '';
    #[Url(as: 'kelas', keep: true)]
    public string $classFilter = '';
    #[Url(as: 'status', keep: true)]
    public string $statusFilter = '';
    public $itemToDeleteId = null;

    // Properti Sorting
    #[Url]
    public string $sortBy = 'created_at'; // Default sort by terbaru
    #[Url]
    public string $sortDirection = 'desc';

    // Properti Form Modal
    public bool $isEditing = false;
    public ?Quiz $editingQuiz = null;

    public $title, $description, $subject_id, $class_id, $category, $duration_minutes, $passing_score, $status, $start_time, $end_time;
    public bool $shuffle_questions = false, $shuffle_options = false;

    // [4] Hapus semua hook 'updating...' karena kita akan menggunakan .live di view

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:classes,id',
            'category' => 'required|in:Ulangan Harian,UTS,UAS,Latihan',
            'duration_minutes' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'shuffle_questions' => 'required|boolean',
            'shuffle_options' => 'required|boolean',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'status' => 'required|in:draft,publish',
        ];
    }

    #[Computed]
    public function quizzes()
    {
        return Quiz::with(['subject', 'targetClass'])->withCount('questions')
            ->where('user_id', Auth::id())
            ->when($this->search, fn($q) => $q->where('title', 'like', '%' . $this->search . '%'))
            ->when($this->subjectFilter, fn($q) => $q->where('subject_id', $this->subjectFilter))
            ->when($this->classFilter, fn($q) => $q->where('class_id', $this->classFilter))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);
    }

    #[Computed]
    public function subjects()
    {
        // [6] Perbarui format subject untuk menyertakan kurikulum
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

    public function sortBy(string $field): void
    {
        $this->sortDirection = $this->sortBy === $field ? ($this->sortDirection === 'asc' ? 'desc' : 'asc') : 'asc';
        $this->sortBy = $field;
    }

    private function resetForm()
    {
        $this->reset(['isEditing', 'editingQuiz', 'title', 'description', 'subject_id', 'class_id', 'category', 'duration_minutes', 'passing_score', 'status', 'start_time', 'end_time', 'shuffle_questions', 'shuffle_options']);
        $this->resetValidation();
    }

    public function create()
    {
        $this->isEditing = false;
        $this->resetForm();
        $this->duration_minutes = 60;
        $this->passing_score = 75;
        $this->status = 'draft';
        $this->category = 'Ulangan Harian';
        $this->dispatch('open-modal', id: 'quiz-form-modal');
    }

    public function edit(Quiz $quiz)
    {
        $this->isEditing = true;
        $this->editingQuiz = $quiz;
        $this->title = $quiz->title;
        $this->description = $quiz->description;
        $this->subject_id = $quiz->subject_id;
        $this->class_id = $quiz->class_id;
        $this->category = $quiz->category;
        $this->duration_minutes = $quiz->duration_minutes;
        $this->passing_score = $quiz->passing_score;
        $this->shuffle_questions = $quiz->shuffle_questions;
        $this->shuffle_options = $quiz->shuffle_options;
        $this->start_time = $quiz->start_time ? $quiz->start_time->format('Y-m-d\TH:i') : null;
        $this->end_time = $quiz->end_time ? $quiz->end_time->format('Y-m-d\TH:i') : null;
        $this->status = $quiz->status;
        $this->dispatch('open-modal', id: 'quiz-form-modal');
    }

    public function save()
    {
        // [7] Logika notifikasi yang disempurnakan
        $wasPreviouslyPublished = $this->isEditing ? $this->editingQuiz->status === 'publish' : false;

        $validatedData = $this->validate();

        if ($this->status === 'publish') {
            $questionCount = $this->isEditing ? $this->editingQuiz->questions()->count() : 0;
            if ($questionCount === 0) {
                $this->addError('status', 'Kuis tidak dapat dipublikasikan karena belum ada soal.');
                return;
            }
        }

        $validatedData['user_id'] = Auth::id();
        $message = 'Kuis berhasil diperbarui.';

        if ($this->isEditing) {
            $this->editingQuiz->update($validatedData);
            $quiz = $this->editingQuiz->fresh();
        } else {
            $quiz = Quiz::create($validatedData);
            $message = 'Kuis berhasil ditambahkan.';
        }

        $isNowPublished = $quiz->status === 'publish';
        if ($isNowPublished && !$wasPreviouslyPublished) {
            $this->sendNotificationToStudents($quiz);
            $message .= ' Notifikasi telah dikirim ke siswa.';
        }

        $this->dispatch('flash-message', ['message' => $message, 'type' => 'success']);
        $this->dispatch('close-modal');
    }

    // [8] Method baru untuk mengirim notifikasi
    private function sendNotificationToStudents(Quiz $quiz)
    {
        try {
            if ($class = Classes::find($quiz->class_id)) {
                $students = $class->users()->whereHas('roles', fn($q) => $q->where('name', 'siswa'))->get();
                if ($students->isNotEmpty()) {
                    Notification::send($students, new NotificationStudent($quiz));
                }
            }
        } catch (\Exception $e) {
            // Log error jika notifikasi gagal
            Log::error('Gagal mengirim notifikasi siswa: ' . $e->getMessage());
            $this->dispatch('flash-message', ['message' => 'Gagal mengirim notifikasi siswa.', 'type' => 'error']);
        }
    }

    public function confirmDelete($id)
    {
        $this->itemToDeleteId = $id;
        $this->dispatch('open-confirm-modal');
    }

    public function delete()
    {
        if ($this->itemToDeleteId) {
            Quiz::findOrFail($this->itemToDeleteId)->delete();
            $this->dispatch('flash-message', ['message' => 'Kuis berhasil dihapus.', 'type' => 'success']);
        }
        $this->dispatch('close-confirm-modal');
        $this->itemToDeleteId = null;
    }

    public function render()
    {
        return view('livewire.teacher.manage-quizzes');
    }
}
