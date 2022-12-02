<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'classroom_id',
        'start_time',
        'end_time',
        'type',
        'class_location',
        'teacher_email',
        'tutor_email',
        'content',
        'attended',
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

    public function inviteLessonMails()
    {
        return $this->hasMany(inviteLessonMails::class, 'lesson_id');
    }
}
