<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'quiz_id',
        'score',
        'correct_answers',
        'incorrect_answers',
        'start_time',
        'end_time',
        'duration_taken',
        'is_completed',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'score' => 'float',
        'correct_answers' => 'integer',
        'incorrect_answers' => 'integer',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'duration_taken' => 'integer',
        'is_completed' => 'boolean',
    ];

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Siswa yang mengerjakan
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class, 'quiz_attempt_id');
    }
}