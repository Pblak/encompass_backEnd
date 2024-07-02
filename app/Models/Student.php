<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends user
{
    use HasFactory;

    protected $table = 'kids'; // Specify the table name explicitly if necessary


    public function instruments()
    {
        return $this->belongsToMany(Instrument::class);
    }

}
