<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{

    const STATUS_PRESENT = 1;
    const STATUS_ABSENT = 0;

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_email',
        'lesson_id',
        'note',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'student_email', 'email');
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }
}
