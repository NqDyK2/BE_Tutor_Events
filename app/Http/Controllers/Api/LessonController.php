<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lesson\CreateLessonRequest;
use App\Http\Requests\Lesson\UpdateLessonRequest;
use App\Models\Classroom;
use App\Services\LessonServices;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    private $lessonServices;
    public function __construct(LessonServices $lessonServices)
    {
        $this->lessonServices = $lessonServices;
    }

    public function index(Request $request, $classroom_id)
    {
        $pageSize = $request->page_size ?? DEFAULT_PAGINATE;
        $lesson = $this->lessonServices->index($classroom_id)->paginate($pageSize);
        return response([
            'Total lesson' => $lesson->total(),
            'Detail lesson' => $lesson->items(),
        ],200);
    }

    public function store(CreateLessonRequest $request)
    {
        $classroom = Classroom::find($request->classroom_id);
        $this->authorize('checkOwnership', $classroom);
        $lesson = $this->lessonServices->store($request->input());
        if ($lesson) {
            return response([
                'data' => $lesson,
                'messages' => 'Create lesson successfully',
                'status' => true,
            ],200);
        }else{
            return response([
                'data' => null,
                'messages' => 'Create lesson failed',
                'status' => false,
            ],400);
        }
    }

    public function update(UpdateLessonRequest $request, $id)
    {
        $classroom = Classroom::find($request->classroom_id);
        $this->authorize('checkOwnership', $classroom);
        $lesson = $this->lessonServices->update($request->input(), $id);
        if ($lesson) {
            return response([
                'data' => $lesson,
                'messages' => 'Update lesson successfully',
                'status' => true,
            ],200);
        }else{
            return response([
                'data' => null,
                'messages' => 'Update lesson failed',
                'status' => false,
            ],400);
        }
    }

    public function destroy($id){
        $lesson = $this->lessonServices->destroy($id);
        if ($lesson) {
            return response([
                'data' => $lesson,
                'messages' => 'Delete lesson successfully',
                'status' => true,
            ],200);
        }else{
            return response([
                'data' => null,
                'messages' => 'Delete lesson failed',
                'status' => false,
            ],400);
        }
    }
}
