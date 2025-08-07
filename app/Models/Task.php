<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'subject_id',
        'class_id', // Relasi ke kelas
        'user_id',
        'due_date',
        'due_time',
        'attachment_path',
        'status',
        'is_published',
        'published_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'due_date' => 'date',
        // PERBAIKAN: Hapus cast time karena Laravel tidak support, biarkan sebagai string
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    // Relasi
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id'); // Guru yang membuat tugas
    }

    public function submissions()
    {
        return $this->hasMany(TaskSubmission::class, 'task_id');
    }

    public function getDueDateTimeAttribute()
    {
        if ($this->due_date && $this->due_time) {
            return \Carbon\Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $this->due_date->format('Y-m-d') . ' ' . $this->due_time
            );
        }
        return null;
    }


    public function getDueTimeFormattedAttribute()
    {
        if ($this->due_time) {
            return \Carbon\Carbon::createFromFormat('H:i:s', $this->due_time)->format('H:i');
        }
        return null;
    }
}