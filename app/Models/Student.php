<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends user
{
    use HasFactory;

    protected $table = 'students';

    public function instruments(): BelongsToMany
    {
        return $this->belongsToMany(Instrument::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Parents::class, 'parent_id');
    }
}
