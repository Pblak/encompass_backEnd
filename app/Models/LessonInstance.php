<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class LessonInstance extends Model
{
    use HasFactory , SoftDeletes;
    protected $fillable = [
        'lesson_id',
        'student_id',
        'teacher_id',
        'start',
        'room_id',
        'status',
    ];
    protected $appends = [
        'teacher',
    ];
    // have student through lesson
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }

    public function room(): HasOne
    {
        return $this->hasOne(Room::class);
    }
    public function getTeacherAttribute()
    {
        if ($this->teacher_id) {
            return Teacher::find($this->teacher_id);
        } else {
            return  Lesson::find($this->lesson_id)->teacher;
        }
    }

}
