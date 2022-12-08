<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classroom extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'subject_id',
        'semester_id',
        'default_teacher_email',
    ];

    public function classStudents()
    {
        return $this->hasMany(ClassStudent::class, 'classroom_id');
    }

    public function joinedStudents()
    {
        return $this->classStudents()->where('is_joined', true);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'classroom_id');
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class, 'classroom_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'default_teacher_email', 'email');
    }

    public function tutor()
    {
        return $this->belongsTo(User::class, 'user_email', 'email');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
