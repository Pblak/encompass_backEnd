<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

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
}
