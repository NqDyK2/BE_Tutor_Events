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
        'content',
        'teacher_email',
        'tutor_email',
        'start_time',
        'end_time',
        'class_location_online',
        'class_location_offline',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_email', 'email');
    }

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_email', 'email');
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'lesson_id');
    }
}
