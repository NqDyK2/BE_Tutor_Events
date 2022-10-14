<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Http\Requests\StoreFeedbackRequest;
use App\Http\Requests\UpdateFeedbackRequest;
use App\Services\FeedbackServices;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    private $feedbackServices;

    public function __construct(FeedbackServices $feedbackServices)
    {
        $this->feedbackServices = $feedbackServices;
    }

    public function store(StoreFeedbackRequest $request)
    {
        $feedBack = $this->feedbackServices->store($request->input());
        if($feedBack) {
            return response([
                'message' => 'Create feedback successfully',
                'data' => $feedBack
            ],201);
        }else{
            return response([
                'message' => 'Create feedback failed'
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

    public function destroy(Request $request)
    {
        $feedBack = $request->get('feedback');
        $feedBackDelete = $this->feedbackServices->destroy($feedBack);

        if($feedBackDelete){
            return response([
                'message' => 'Delete Feedback successfully',
            ],200);
        } else {
            return response([
                'massage' => 'Delete Feedback false'
            ],400);
        }
    }
}
