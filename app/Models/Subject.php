<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'kurikulum',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi
    public function materials()
    {
        return $this->hasMany(Material::class, 'subject_id');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'subject_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'subject_id');
    }

    public function educationalGames()
    {
        return $this->hasMany(EducationalGame::class, 'subject_id');
    }
}
