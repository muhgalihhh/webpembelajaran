<?php

namespace App\Livewire\Teacher;

use App\Models\Classes;
use App\Models\Quiz;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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
    #[Url(as: 'q')]
    public string $search = '';
    #[Url(as: 'mapel')]
    public string $subjectFilter = '';
    #[Url(as: 'kelas')]
    public string $classFilter = '';
    #[Url(as: 'status')]
    public string $statusFilter = '';
    public $itemToDeleteId = null;

    // Properti Sorting
    #[Url]
    public string $sortBy = 'title';
    #[Url]
    public string $sortDirection = 'asc';

    // Properti Form Modal
    public bool $isEditing = false;
    public ?Quiz $editingQuiz = null;

    public $title, $description, $subject_id, $class_id, $category, $duration_minutes, $passing_score, $status, $start_time, $end_time;
    public bool $shuffle_questions = false, $shuffle_options = false;

    // Lifecycle Hooks
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingSubjectFilter()
    {
        $this->resetPage();
    }
    public function updatingClassFilter()
    {
        $this->resetPage();
    }
    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

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
            'status' => 'required|in:draft,published,archived',
        ];
    }

    #[Computed]
    public function quizzes()
    {
        return Quiz::with(['subject', 'targetClass'])
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
        return Subject::orderBy('name')->get();
    }

    #[Computed]
    public function classes()
    {
        return Classes::orderBy('class')->get();
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
        $validatedData = $this->validate();

        // Validasi kustom: Kuis tidak boleh di-publish jika tidak ada soal
        Validator::make($validatedData, [])->after(function ($validator) {
            $questionCount = $this->isEditing ? $this->editingQuiz->questions()->count() : 0;
            if ($this->status === 'published' && $questionCount === 0) {
                $validator->errors()->add(
                    'status',
                    'Kuis tidak dapat dipublikasikan karena belum memiliki soal.'
                );
            }
        })->validate();

        $validatedData['user_id'] = Auth::id();

        // --- PERBAIKAN: Menambahkan start_date dan end_date ---
        $validatedData['start_date'] = $this->start_time ? date('Y-m-d', strtotime($this->start_time)) : null;
        $validatedData['end_date'] = $this->end_time ? date('Y-m-d', strtotime($this->end_time)) : null;

        if ($this->isEditing) {
            $this->editingQuiz->update($validatedData);
            $message = 'Kuis berhasil diperbarui.';
        } else {
            // Nilai default untuk kuis baru
            $validatedData['total_questions'] = 0;
            $validatedData['score_weight'] = 100;

            Quiz::create($validatedData);
            $message = 'Kuis berhasil ditambahkan.';
        }
        $this->dispatch('flash-message', ['message' => $message, 'type' => 'success']);
        $this->dispatch('close-modal');
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