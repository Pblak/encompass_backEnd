<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LessonInstance extends Model
{
    use HasFactory , SoftDeletes;
    protected $fillable = [
        'lesson_id',
        'student_id',
        'start',
        'room_id',
        'status',
    ];

    // have student through lesson
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
