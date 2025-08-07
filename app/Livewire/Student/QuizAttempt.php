<?php

namespace App\Livewire\Student;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAttempt as Attempt;
use App\Models\StudentAnswer;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.landing')]
#[Title('Mengerjakan Kuis')]
class QuizAttempt extends Component
{
    public Quiz $quiz;
    public $questions;
    public int $currentQuestionIndex = 0;
    public array $userAnswers = [];
    public ?Attempt $attempt = null;
    public ?int $timeRemaining = null;
    public bool $showFinishConfirmation = false;

    // Properti untuk menyimpan pemetaan urutan pilihan yang sudah diacak
    private array $shuffledOptionMaps = [];

    public function mount(Quiz $quiz)
    {
        $this->quiz = $quiz;

        $anyAttempt = Attempt::where('user_id', Auth::id())
            ->where('quiz_id', $this->quiz->id)
            ->first();

        if ($anyAttempt) {
            if ($anyAttempt->is_completed) {
                session()->flash('flash-message', [
                    'message' => 'Anda sudah pernah menyelesaikan kuis ini. Berikut adalah hasilnya.',
                    'type' => 'info'
                ]);
                return $this->redirect(route('student.quizzes.result', $anyAttempt->id), navigate: true);
            }

            $this->resumeQuiz($anyAttempt);

        } else {
            $this->startNewQuiz();
        }

        if (isset($this->questions) && $this->questions->isEmpty()) {
            session()->flash('flash-message', [
                'message' => 'Kuis ini belum memiliki soal.',
                'type' => 'warning'
            ]);
            $this->redirect(route('student.quizzes'), navigate: true);
        }
    }

    public function startNewQuiz()
    {
        $allQuestions = $this->quiz->questions();

        if ($this->quiz->shuffle_questions) {
            $this->questions = $allQuestions->inRandomOrder()->get();
        } else {
            $this->questions = $allQuestions->get();
        }

        if ($this->questions->isEmpty())
            return;

        $questionOrder = $this->questions->pluck('id')->toArray();

        $this->attempt = Attempt::create([
            'user_id' => Auth::id(),
            'quiz_id' => $this->quiz->id,
            'start_time' => now(),
            'question_order' => json_encode($questionOrder),
        ]);

        $this->timeRemaining = $this->quiz->duration_minutes * 60;
        $this->userAnswers = array_fill(0, $this->questions->count(), null);
    }

    public function resumeQuiz(Attempt $attempt)
    {
        $this->attempt = $attempt;
        $questionOrder = $this->attempt->question_order;

        if (is_string($questionOrder)) {
            $questionOrder = json_decode($questionOrder, true);
        }

        if (!is_array($questionOrder) || empty($questionOrder)) {
            $allQuestionsQuery = $this->quiz->questions();
            if ($this->quiz->shuffle_questions) {
                $questionsCollection = $allQuestionsQuery->inRandomOrder()->get();
            } else {
                $questionsCollection = $allQuestionsQuery->get();
            }
            $questionOrder = $questionsCollection->pluck('id')->toArray();

            $this->attempt->update(['question_order' => $questionOrder]);
            $this->attempt->refresh();
            $questionOrder = $this->attempt->question_order;
        }

        $this->questions = Question::whereIn('id', $questionOrder)
            ->get()
            ->sortBy(function ($question) use ($questionOrder) {
                return array_search($question->id, $questionOrder);
            });

        // --- PERBAIKAN AKURASI WAKTU ---
        // 1. Ambil waktu mulai dari database.
        $startTime = $this->attempt->start_time;

        // 2. Hitung waktu deadline kuis (waktu mulai + durasi).
        //    Ini menciptakan titik akhir yang tetap dan tidak berubah saat di-refresh.
        $deadline = $startTime->copy()->addMinutes($this->quiz->duration_minutes);

        // 3. Hitung sisa detik dari SEKARANG hingga waktu deadline.
        //    Argumen `false` memungkinkan hasil negatif jika waktu sudah habis.
        $remainingSeconds = now()->diffInSeconds($deadline, false);

        // 4. Pastikan sisa waktu tidak kurang dari 0.
        $this->timeRemaining = max(0, $remainingSeconds);

        $this->loadExistingAnswers();
    }

    public function loadExistingAnswers()
    {
        $savedAnswers = StudentAnswer::where('quiz_attempt_id', $this->attempt->id)
            ->pluck('chosen_option', 'question_id')
            ->toArray();

        $this->userAnswers = [];
        foreach ($this->questions as $index => $question) {
            $savedOriginalKey = $savedAnswers[$question->id] ?? null;
            if ($savedOriginalKey) {
                if ($this->quiz->shuffle_options) {
                    $map = $this->getShuffledOptionMap($question);
                    $invertedMap = array_flip($map);
                    $this->userAnswers[$index] = $invertedMap[$savedOriginalKey] ?? null;
                } else {
                    $this->userAnswers[$index] = $savedOriginalKey;
                }
            } else {
                $this->userAnswers[$index] = null;
            }
        }
    }

    public function updatedUserAnswers($value, $key)
    {
        $question = $this->questions[$key];
        $originalAnswerKey = $value;

        if ($this->quiz->shuffle_options) {
            $map = $this->getShuffledOptionMap($question);
            $originalAnswerKey = $map[$value] ?? null;
        }

        if ($originalAnswerKey) {
            StudentAnswer::updateOrCreate(
                [
                    'quiz_attempt_id' => $this->attempt->id,
                    'question_id' => $question->id,
                ],
                [
                    'chosen_option' => $originalAnswerKey,
                    'is_correct' => ($originalAnswerKey === $question->correct_option),
                ]
            );
        }
    }

    private function getShuffledOptionMap(Question $question): array
    {
        $questionId = $question->id;
        if (isset($this->shuffledOptionMaps[$questionId])) {
            return $this->shuffledOptionMaps[$questionId];
        }

        $options = [
            'A' => $question->option_a,
            'B' => $question->option_b,
            'C' => $question->option_c,
            'D' => $question->option_d,
            'E' => $question->option_e,
        ];
        $options = array_filter($options, fn($val) => !is_null($val) && $val !== '');

        $originalKeys = array_keys($options);

        $seed = crc32($this->attempt->id . '-' . $questionId);
        mt_srand($seed);
        shuffle($originalKeys);
        mt_srand();

        $displayKeys = array_slice(['A', 'B', 'C', 'D', 'E'], 0, count($originalKeys));

        return $this->shuffledOptionMaps[$questionId] = array_combine($displayKeys, $originalKeys);
    }

    #[Computed]
    public function currentQuestionOptions(): array
    {
        if (!isset($this->questions[$this->currentQuestionIndex])) {
            return [];
        }

        $currentQuestion = $this->questions[$this->currentQuestionIndex];

        $options = [
            'A' => $currentQuestion->option_a,
            'B' => $currentQuestion->option_b,
            'C' => $currentQuestion->option_c,
            'D' => $currentQuestion->option_d,
            'E' => $currentQuestion->option_e,
        ];
        $options = array_filter($options, fn($val) => !is_null($val) && $val !== '');

        if ($this->quiz->shuffle_options) {
            $map = $this->getShuffledOptionMap($currentQuestion);
            $shuffledDisplayOptions = [];
            foreach ($map as $displayKey => $originalKey) {
                $shuffledDisplayOptions[$displayKey] = $options[$originalKey];
            }
            return $shuffledDisplayOptions;
        }

        return $options;
    }

    public function nextQuestion()
    {
        if ($this->currentQuestionIndex < $this->questions->count() - 1) {
            $this->currentQuestionIndex++;
        }
    }

    public function previousQuestion()
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
        }
    }

    public function confirmFinish()
    {
        $this->showFinishConfirmation = true;
    }

    public function cancelFinish()
    {
        $this->showFinishConfirmation = false;
    }

    public function submitQuiz()
    {
        if (!$this->attempt || $this->attempt->is_completed) {
            return;
        }

        $allQuestionsInQuiz = $this->questions;
        $maxPossibleWeight = $allQuestionsInQuiz->sum('weight');
        $correctlyAnsweredQuestionIds = $this->attempt
            ->studentAnswers()
            ->where('is_correct', true)
            ->pluck('question_id');
        $totalEarnedWeight = $allQuestionsInQuiz
            ->whereIn('id', $correctlyAnsweredQuestionIds)
            ->sum('weight');
        $score = ($maxPossibleWeight > 0) ? ($totalEarnedWeight / $maxPossibleWeight) * 100 : 0;
        $correctAnswersCount = $correctlyAnsweredQuestionIds->count();
        $totalQuestionsCount = $allQuestionsInQuiz->count();
        $endTime = now();
        $durationTaken = $endTime->diffInSeconds($this->attempt->start_time);

        $this->attempt->update([
            'score' => $score,
            'correct_answers' => $correctAnswersCount,
            'incorrect_answers' => $totalQuestionsCount - $correctAnswersCount,
            'end_time' => $endTime,
            'duration_taken' => $durationTaken,
            'is_completed' => true,
        ]);

        $this->showFinishConfirmation = false;
        return $this->redirect(route('student.quizzes.result', $this->attempt->id), navigate: true);
    }

    public function render()
    {
        return view('livewire.student.quiz-attempt', [
            'currentQuestion' => $this->questions[$this->currentQuestionIndex] ?? null
        ]);
    }
}