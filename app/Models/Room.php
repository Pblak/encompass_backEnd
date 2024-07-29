<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'capacity'];

    // Room has many lessons that have the same instrument
    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'room_id', 'id');
    }

}
