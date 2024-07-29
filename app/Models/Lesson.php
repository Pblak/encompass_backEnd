<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'instrument_id',
        'student_id',
        'room_id',
        'instrument_plan',
        'frequency',
        'planning',
        'duration',
    ];

    protected $casts = [
        'planning' => 'array',
        'instrument_plan' => 'array',
    ];

    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'id', 'teacher_id');
    }

    public function student()
    {
        return $this->hasOne(Student::class, 'id', 'student_id');
    }

    public function instrument()
    {
        return $this->hasOne(Instrument::class, 'id', 'instrument_id');
    }

    public function room()
    {
        return $this->hasOne(Room::class, 'id', 'room_id');
    }

    public function instances()
    {
        return $this->hasMany(LessonInstance::class);
    }
}
