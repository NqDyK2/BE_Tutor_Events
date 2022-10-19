<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Semester extends Model
{
    use HasFactory, SoftDeletes;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'start_time',
        'end_time',
    ];

    public function classrooms()
    {
        return $this->hasMany(Classroom::class, 'semester_id');
    }

    public function lessons()
    {
        return $this->hasManyThrough(Lesson::class, Classroom::class);
    }
}
