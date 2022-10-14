<?php
namespace App\Services;

use App\Models\Feedback;

Class FeedbackServices
{
    public function store($data){
        return Feedback::create($data);
    }

    public function feedbackInLesson($id)
    {
        $feedBack = Feedback::where('lesson_id', $id)->get();
        return $feedBack;
    }

    public function destroy($id)
    {
        $feedBack = Feedback::find($id);
        return $feedBack->delete();
    }
}