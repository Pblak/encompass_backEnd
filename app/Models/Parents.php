<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Parents extends User
{
    use HasFactory, HasApiTokens, SoftDeletes , Notifiable;


    protected $table = 'parents';
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'infos'
    ];
    protected $casts = [
        'infos' => 'array',
    ];
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'parent_id');
    }

    public function lessons(): HasManyThrough
    {
        return $this->hasManyThrough(Lesson::class, Student::class, 'parent_id', 'student_id');
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactional');
    }

    protected static function boot(): void
    {
        parent::boot();
        static::deleting(function ($parents) {
            $parents->students()->each(function ($student) {
                $student->delete();
            });
        });
    }
}
