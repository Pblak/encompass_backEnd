<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;


class Teacher extends User
{
        use HasFactory, HasApiTokens , SoftDeletes;
    protected $table = 'teachers';
    // Override the default Eloquent methods as needed
//    public static function get($columns = ['*'])
//    {
//        return static::ofType('teacher')->get($columns);
//    }
//    public static function all($columns = ['*'])
//    {
//        return static::ofType('teacher')->get($columns);
//    }

    public function instruments()
    {
        return $this->belongsToMany(Instrument::class, 'instrument_teacher', 'user_id', 'instrument_id');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }
}





/*public static function find($id, $columns = ['*'])
{
    return static::ofType('teacher')->find($id, $columns);
}

public static function with($relations)
{
    return static::ofType('teacher')->with($relations);
}*/
