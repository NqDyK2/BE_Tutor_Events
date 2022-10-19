<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Http\Requests\StoreFeedbackRequest;
use App\Http\Requests\UpdateFeedbackRequest;
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
        $feedBack = Feedback::where('lesson_id',$request->lesson_id)->where('user_id',Auth::id())->first();
        if($feedBack == null){
            $feedBackNew = $this->feedbackServices->store($request->input());
            if($feedBackNew) {
                return response([
                    'message' => 'Create feedback successfully',
                ],201);
            }else{
                return response([
                    'message' => 'Create feedback failed'
                ],500);
            }
        }else{
            return response([
                'message' => 'Sinh viên đã FEED BACK'
            ],500);
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
