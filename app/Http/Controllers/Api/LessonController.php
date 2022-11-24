<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lesson\CreateLessonRequest;
use App\Http\Requests\Lesson\UpdateLessonRequest;
use App\Models\Classroom;
use App\Services\BreadcrumbServices;
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
        $this->lessonServices->store($request->input());
        return response([
            'message' => 'Tạo buổi học thành công'
        ], 201);
    }

    public function update(UpdateLessonRequest $request)
    {
        $lesson = $request->get('lesson');
        $classroom = Classroom::find($request->classroom_id);
        $this->authorize('teacherOfClass', $classroom, $lesson);
        $this->lessonServices->update($request->input(), $lesson);
        return response([
            'message' => 'Cập nhật buổi học thành công'
        ], 200);
    }

    public function destroy(Request $request)
    {
        $lesson = $request->get('lesson');
        $classroom = Classroom::find($lesson->classroom_id);

        $this->authorize('teacherOfClass', $classroom, $lesson);

        $response = $this->lessonServices->destroy($lesson->id);

        return $response;
    }
}
