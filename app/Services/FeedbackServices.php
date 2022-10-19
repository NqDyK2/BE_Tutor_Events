<?php
namespace App\Services;

use App\Models\Feedback;
use Illuminate\Support\Facades\DB;

Class FeedbackServices
{
    public function store($data){
        return Feedback::updateOrCreate(
            [
                'lesson_id' => $data['lesson_id'],
                'user_id' => $data['user_id'],
            ],
            [
                'lesson_quality' => $data['lesson_quality'],
                'teacher_quality' => $data['teacher_quality'],
                'support_quality' => $data['support_quality'],
                'understand_lesson' => $data['understand_lesson'],
                'message' => $data['message'],
                'note' => $data['note'],
            ],
        );
    }

    public function feedbackInLesson($id)
    {
        $feedBack = Feedback::where('lesson_id', $id)->get();
        // $feedBack = DB::table('feedbacks')->where('lesson_id', $id)->get();
        return $feedBack;
    }

    public function destroy($id)
    {
        $feedBack = Feedback::find($id);
        return $feedBack->delete();
    }
}