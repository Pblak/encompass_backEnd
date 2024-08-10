<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Student extends user
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'parent_id',
        'infos'
    ];

    protected $casts = [
        'infos' => 'array',
    ];

    public function instruments(): BelongsToMany
    {
        return $this->belongsToMany(Instrument::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Parents::class, 'parent_id');
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactional');
    }
}
