<?php

namespace App\Imports;

use App\Models\Question;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuestionsImport implements ToModel, WithHeadingRow
{
    protected $quizId;

    public function __construct($quizId)
    {
        $this->quizId = $quizId;
    }

    public function model(array $row)
    {
        // Hitung jumlah soal saat ini
        $currentCount = Question::where('quiz_id', $this->quizId)->count();

        return new Question([
            'quiz_id'         => $this->quizId,
            'question_number' => $currentCount + 1, 
            'question_text'   => $row['pertanyaan'],
            'option_a'        => $row['opsi_a'],
            'option_b'        => $row['opsi_b'],
            'option_c'        => $row['opsi_c'],
            'option_d'        => $row['opsi_d'],
            'option_e'        => $row['opsi_e'] ?? null,
            'correct_option'  => strtoupper($row['kunci']),
            'weight'          => $row['bobot'] ?? 1,
            'explanation'     => $row['penjelasan'] ?? null,
        ]);
    }
}
