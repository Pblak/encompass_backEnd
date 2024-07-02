<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends User
{
    protected $table = 'users'; // Specify the table name explicitly if necessary

    // Override the default Eloquent methods as needed
    public static function get($columns = ['*'])
    {
        return static::ofType('teacher')->get($columns);
    }
    public static function all($columns = ['*'])
    {
        return static::ofType('teacher')->get($columns);
    }

    public function instruments()
    {
        return $this->belongsToMany(Instrument::class);
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
