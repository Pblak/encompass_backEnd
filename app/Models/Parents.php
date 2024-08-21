<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Laravel\Sanctum\HasApiTokens;

class Parents extends User
{
    use HasFactory , HasApiTokens;


    protected $table = 'parents';
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'parent_id');
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactional');
    }
}
