<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = [
        'teacher_id',
        'instrument_id',
        'student_id',
        'room_id',
        'instrument_plan',
        'frequency',
        'planning',
        'duration',
    ];

    protected $casts = [
        'planning' => 'array',
        'instrument_plan' => 'array',
    ];

    protected $appends = [
        'price',
        'payed_price',
    ];

    public function teacher(): HasOne
    {
        return $this->hasOne(Teacher::class, 'id', 'teacher_id');
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class, 'id', 'student_id');
    }

    public function instrument(): HasOne
    {
        return $this->hasOne(Instrument::class, 'id', 'instrument_id');
    }

    public function room(): HasOne
    {
        return $this->hasOne(Room::class, 'id', 'room_id');
    }

    public function instances(): HasMany
    {
        return $this->hasMany(LessonInstance::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'lesson_id','id');
    }

    public function getPriceAttribute(): float|int
    {
        if (!$this->instrument_plan) return 0;
        $total = collect($this->planning)->reduce(function ($acc, $val){
            return $acc + count($val);
    }, 0);
        return $total * intval($this->instrument_plan['price']) * $this->frequency;
    }

    public function getPayedPriceAttribute()
    {
        return $this->transactions()
            ->where('status','completed')->get()
            ->reduce(function ($acc, $val){
            return $acc + $val->amount;
        }, 0);
    }

    protected static function boot(): void
    {
        parent::boot();
        static::deleting(function ($lesson) {
            $lesson->instances()->each(function ($instance) {
                $instance->delete();
            });
        });
    }

}
