<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lesson\CreateLessonRequest;
use App\Http\Requests\Lesson\UpdateLessonRequest;
use App\Models\Classroom;
use App\Services\BreadcrumbServices;
use App\Services\ClassroomServices;
use App\Services\LessonServices;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    private $lessonServices;
    private $breadcrumbServices;

    public function __construct(
        LessonServices $lessonServices,
        BreadcrumbServices $breadcrumbServices
    ) {
        $this->lessonServices = $lessonServices;
        $this->breadcrumbServices = $breadcrumbServices;
    }

    public function lessonsInClassroom(Request $request)
    {
        $classroomId = $request->classroom_id;

        $lesson = $this->lessonServices->lessonsInClassroom($classroomId);
        $tree = $this->breadcrumbServices->getByClassroom($classroomId);

        return response([
            'data' => $lesson,
            'tree' => $tree
        ], 200);
    }

    public function store(CreateLessonRequest $request)
    {
        $classroom = Classroom::find($request->classroom_id);
        $this->authorize('teacherOfClass', $classroom);
        $lesson = $this->lessonServices->store($request->input());
        if ($lesson) {
            return response([
                'message' => 'Create lesson successfully'
            ], 201);
        } else {
            return response([
                'message' => 'Create lesson failed'
            ], 500);
        }
    }

    public function update(UpdateLessonRequest $request)
    {
        $lesson = $request->get('lesson');
        $classroom = Classroom::find($request->classroom_id);
        $this->authorize('teacherOfClass', $classroom);
        $lesson = $this->lessonServices->update($request->input(), $lesson);
        if ($lesson) {
            return response([
                'message' => 'Update lesson successfully'
            ], 200);
        } else {
            return response([
                'message' => 'Update lesson failed'
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        $lesson = $request->get('lesson');
        $classroom = Classroom::find($lesson->classroom_id);

        $this->authorize('teacherOfClass', $classroom);

        $response = $this->lessonServices->destroy($lesson->id);

        return $response;
    }

    public function studentSchedule()
    {
        $lesson = $this->lessonServices->studentSchedule();
        return response([
            'data' => $lesson,
        ], 200);
    }
}
