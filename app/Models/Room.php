<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = ['name', 'capacity', 'notes'];

    // Room has many lessons that have the same instrument
    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'room_id', 'id');
    }

}
