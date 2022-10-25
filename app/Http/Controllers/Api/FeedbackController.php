<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FeedBack\StoreFeedbackRequest;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Feedback;
use App\Models\Lesson;
use App\Models\Semester;
use App\Models\User;
use App\Services\FeedbackServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    private $feedbackServices;

    public function __construct(FeedbackServices $feedbackServices)
    {
        $this->feedbackServices = $feedbackServices;
    }

    public function store(StoreFeedbackRequest $request)
    {
        $user = User::find(Auth::id());
        $lesson = Lesson::find($request->lesson_id);
        $classroom = Classroom::find($lesson->classroom_id);
        $semester = Semester::find($classroom->semester_id);
        if(!($semester->start_time <= date('Y-m-d H:i:s') && date('Y-m-d H:i:s') <= $semester->end_time)) {
            return response([
                'message' => 'Học kỳ đã kết thúc'
            ],400); 
        }
        $attendances = Attendance::where('lesson_id',$request->lesson_id)->where('student_email',$user->email)->first();
        if($attendances == null) {
            return response([
                'message' => 'Sinh viên không có trong buổi học'
            ],400); 
        }
        $feedBackNew = $this->feedbackServices->store($request->input());
        if($feedBackNew) {
            return response([
                'message' => 'Create feedback successfully',
            ],201);
        }else{
            return response([
                'message' => 'Sinh viên đã Feed Back'
            ],400);
        }
    }

    public function feedbackInLesson($lesson_id)
    {
        $feedBack = $this->feedbackServices->feedbackInLesson($lesson_id);
        return response([
            'data' => $feedBack
        ],200);
    }
}
