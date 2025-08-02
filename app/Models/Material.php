<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
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
        'description',
        'page_count',
        'file_path',
        'content',
        'youtube_url',
        'chapter',
        'is_published',
        'published_at',
        'class_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    // Relasi
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function accessLogs()
    {
        return $this->hasMany(MaterialAccessLog::class);
    }
}
