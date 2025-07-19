<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'subject_id',
        'user_id',
        'class_id',
        'description',
        'category',
        'total_questions',
        'duration_minutes',
        'score_weight',
        'passing_score',
        'shuffle_questions',
        'shuffle_options',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_questions' => 'integer',
        'duration_minutes' => 'integer',
        'score_weight' => 'integer',
        'passing_score' => 'integer',
        'shuffle_questions' => 'boolean',
        'shuffle_options' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime', // Cast to datetime to use Carbon methods
        'end_time' => 'datetime',   // Cast to datetime to use Carbon methods
    ];

    // Relasi
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id'); // Guru yang membuat kuis
    }

    public function targetClass()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'quiz_id');
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class, 'quiz_id');
    }
}