<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Parents extends User
{
    use HasFactory;


    protected $table = 'parents';
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'parent_id');
    }
}
