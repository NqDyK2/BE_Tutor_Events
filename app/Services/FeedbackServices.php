<?php

namespace App\Services;

use App\Models\Feedback;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FeedbackServices
{
    public function store($data)
    {
        $feedBack = Feedback::where('lesson_id', $data['lesson_id'])->where('user_id', Auth::id())->first();
        if ($feedBack == null) {
            $data['user_id'] = Auth::id();
            return Feedback::create($data);
        }
        if (!$feedBack) {
            return False;
        }
    }

    public function feedbackInLesson($id)
    {
        $feedBack = Feedback::where('lesson_id', $id)->join('users', 'users.id', '=', 'feedback.user_id')
            ->select(
                'feedback.lesson_id',
                'users.code',
                'feedback.lesson_quality',
                'feedback.teacher_quality',
                'feedback.support_quality',
                'feedback.understand_lesson',
                'feedback.message',
                'feedback.note'
            )->get();
        return $feedBack;
    }
}
