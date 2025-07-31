<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;
    protected $table = 'classes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'class',
        'description',
    ];

    // Relasi
    public function users()
    {
        return $this->hasMany(User::class, 'class_id');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'class_id');
    }
    public function materials()
    {
        return $this->hasMany(Material::class, 'class_id');
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->class . ' ' . $this->name,
        );
    }
}