<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'classroom_id',
        'type',
        'class_location',
        'start_time',
        'end_time',
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class,'classroom_id');
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class,'lesson_id');
    }
}
