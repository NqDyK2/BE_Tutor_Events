<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lesson\CreateLessonRequest;
use App\Http\Requests\Lesson\UpdateLessonRequest;
use App\Http\Services\BreadcrumbServices;
use App\Http\Services\LessonServices;
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
        $convertLessonTime = convertLessonTime($request->date, $request->lesson_number);
        $data = array_merge($request->input(), $convertLessonTime);
        $response = $this->lessonServices->store( $data);

        return $response;
    }

    public function update(UpdateLessonRequest $request)
    {
        $lesson = $request->get('lesson');
        $convertLessonTime = convertLessonTime($request->date, $request->lesson_number);
        $data = array_merge($request->input(), $convertLessonTime);
        $response = $this->lessonServices->update($data, $lesson);

        return $response;
    }

    public function start(Request $request)
    {
        $lesson = $request->get('lesson');
        $response = $this->lessonServices->startLesson($lesson);

        return $response;
    }

    public function destroy(Request $request)
    {
        $lesson = $request->get('lesson');
        $response = $this->lessonServices->destroy($lesson);

        return $response;
    }
}
