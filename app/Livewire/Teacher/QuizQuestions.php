<?php

namespace App\Livewire\Teacher;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.teacher')]
#[Title('Manajemen Soal Kuis')]
class QuizQuestions extends Component
{
    use WithFileUploads;

    public Quiz $quiz;

    // Properti untuk state
    public bool $isEditing = false;
    public ?Question $editingQuestion = null;
    public $itemToDeleteId = null;

    // Properti Form dengan Aturan Validasi Livewire 3
    #[Rule('required|string|min:5', as: 'Teks Pertanyaan')]
    public string $question_text = '';

    #[Rule('nullable|image|max:2048', as: 'Gambar')]
    public $uploadedImage;

    public ?string $image_path = null;

    #[Rule('required|string', as: 'Opsi A')]
    public string $option_a = '';

    #[Rule('required|string', as: 'Opsi B')]
    public string $option_b = '';

    #[Rule('required|string', as: 'Opsi C')]
    public string $option_c = '';

    #[Rule('required|string', as: 'Opsi D')]
    public string $option_d = '';

    #[Rule('nullable|string', as: 'Opsi E')]
    public string $option_e = '';

    #[Rule('required|in:A,B,C,D,E', as: 'Kunci Jawaban')]
    public string $correct_option = 'A';

    #[Rule('nullable|string', as: 'Penjelasan')]
    public string $explanation = '';

    #[Rule('required|integer|min:1', as: 'Bobot Nilai')]
    public int $weight = 1;

    public function mount(Quiz $quiz)
    {
        $this->quiz = $quiz;
    }

    private function updateQuestionCount()
    {
        $this->quiz->update(['total_questions' => $this->quiz->questions()->count()]);
    }

    private function resetForm()
    {
        $this->reset(['isEditing', 'editingQuestion', 'question_text', 'uploadedImage', 'image_path', 'option_a', 'option_b', 'option_c', 'option_d', 'option_e', 'correct_option', 'explanation', 'weight']);
        $this->correct_option = 'A';
        $this->weight = 1;
        $this->resetValidation();
    }

    public function create()
    {
        $this->isEditing = false;
        $this->resetForm();
        $this->dispatch('open-modal', id: 'question-form-modal');
    }

    public function edit(Question $question)
    {
        $this->isEditing = true;
        $this->editingQuestion = $question;
        $this->question_text = $question->question_text;
        $this->image_path = $question->image_path;
        $this->option_a = $question->option_a;
        $this->option_b = $question->option_b;
        $this->option_c = $question->option_c;
        $this->option_d = $question->option_d;
        $this->option_e = $question->option_e;
        $this->correct_option = $question->correct_option;
        $this->explanation = $question->explanation;
        $this->weight = $question->weight;
        $this->dispatch('open-modal', id: 'question-form-modal');
    }

    public function save()
    {
        $validatedData = $this->validate();

        $dataToSave = [
            'question_text' => $this->question_text,
            'option_a' => $this->option_a,
            'option_b' => $this->option_b,
            'option_c' => $this->option_c,
            'option_d' => $this->option_d,
            'option_e' => $this->option_e,
            'correct_option' => $this->correct_option,
            'explanation' => $this->explanation,
            'weight' => $this->weight,
        ];

        if ($this->uploadedImage) {
            if ($this->isEditing && $this->editingQuestion->image_path) {
                Storage::disk('public')->delete($this->editingQuestion->image_path);
            }
            $dataToSave['image_path'] = $this->uploadedImage->store('question-images', 'public');
        }

        if ($this->isEditing) {
            $this->editingQuestion->update($dataToSave);
            $message = 'Soal berhasil diperbarui.';
        } else {
            $dataToSave['question_number'] = $this->quiz->questions()->count() + 1;
            $this->quiz->questions()->create($dataToSave);
            $message = 'Soal berhasil ditambahkan.';
        }

        $this->updateQuestionCount();

        // --- PERBAIKAN: Menggunakan dispatch event untuk notifikasi ---
        $this->dispatch('flash-message', message: $message, type: 'success');
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
            $question = Question::find($this->itemToDeleteId);
            if ($question) {
                if ($question->image_path) {
                    Storage::disk('public')->delete($question->image_path);
                }
                $question->delete();
                $this->updateQuestionCount();

                // --- PERBAIKAN: Menggunakan dispatch event untuk notifikasi ---
                $this->dispatch('flash-message', message: 'Soal berhasil dihapus.', type: 'success');
            }
        }
        $this->dispatch('close-confirm-modal');
        $this->itemToDeleteId = null;
    }

    public function render()
    {
        $questions = $this->quiz->questions()->orderBy('question_number')->get();
        return view('livewire.teacher.quiz-questions', compact('questions'));
    }
}