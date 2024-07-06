<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instrument extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image'];

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class , 'instrument_teacher' , 'instrument_id' , 'user_id'  );
    }

    public function students()
    {
        return $this->belongsToMany(Student::class ,
            'instrument_student' ,
            'instrument_id',
            'student_id');
    }
}
