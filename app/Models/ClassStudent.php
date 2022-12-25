<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassStudent extends Model
{
    use HasFactory;

    const FINAL_RESULT_PASSED = 1;
    const FINAL_RESULT_NOT_PASSED = 0;
    const FINAL_RESULT_BANNED = -1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'classroom_id',
        'student_email',
        'reason',
        'final_result',
        'final_score',
        'is_warning',
        'is_sent_mail'
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'student_email', 'email');
    }

    public function feedback()
    {
        return $this->belongsTo(User::class, 'student_email', 'student_email');
    }
}
