<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parents extends User
{
    use HasFactory;
    protected $table = 'users'; // Specify the table name explicitly if necessary

    public static function get($columns = ['*'])
    {
        return static::ofType('parent')->get($columns);
    }
    public static function all($columns = ['*'])
    {
        return static::ofType('parent')->get($columns);
    }
}
