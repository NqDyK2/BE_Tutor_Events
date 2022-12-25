<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InviteLessonMail extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_email',
        'lesson_id',
    ];
}
