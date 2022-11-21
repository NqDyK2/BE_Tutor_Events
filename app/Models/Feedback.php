<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'classroom_quality',
        'teacher_quality',
        'tutor_quality',
        'understand',
        'message',
        'classroom_id',
        'user_id',
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
