<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // Import Spatie HasRoles trait

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles; // Gunakan HasRoles trait

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'class_id', // Nullable, jadi bisa diisi jika siswa
        'status',
        'profile_picture',
        'phone_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id'); // Pastikan nama model sesuai
    }

    public function uploadedMaterials()
    {
        return $this->hasMany(Material::class, 'user_id');
    }

    public function createdQuizzes()
    {
        return $this->hasMany(Quiz::class, 'user_id');
    }

    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class, 'user_id');
    }

    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'user_id');
    }

    public function taskSubmissions()
    {
        return $this->hasMany(TaskSubmission::class, 'user_id');
    }

    // Tambahkan ini di dalam class User
    public function materialAccessLogs()
    {
        return $this->hasMany(MaterialAccessLog::class);
    }

    public function lastAccessedMaterials()
    {
        return $this->belongsToMany(Material::class, 'material_access_logs')
            ->withPivot('accessed_at')
            ->orderByPivot('accessed_at', 'desc');
    }
}
