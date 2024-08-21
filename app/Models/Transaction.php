<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        "id",
        "transactional_id",
        "transactional_type",
        "lesson_id",
        "amount",
        "currency",
        "status",
        "payment_method",
        "notes",

    ];
    public function transactional(): MorphTo
    {
        return $this->morphTo();
    }
}
