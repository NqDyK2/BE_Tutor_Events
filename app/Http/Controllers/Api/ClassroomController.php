<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Classroom\CreateClassroomRequest;
use App\Http\Requests\Classroom\UpdateClassroomRequest;
use App\Http\Requests\Feedback\StoreFeedbackRequest;
use App\Http\Requests\FeedbackClassroomRequest;
use App\Http\Services\BreadcrumbServices;
use App\Http\Services\ClassroomServices;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    private $classroomServices;
    private $breadcrumbServices;

    public function __construct(ClassroomServices $classroomServices, BreadcrumbServices $breadcrumbServices)
    {
        $this->classroomServices = $classroomServices;
        $this->breadcrumbServices = $breadcrumbServices;
    }

    public function classroomsInSemester(Request $request)
    {
        $semester = $request->get('semester');
        $classroom = $this->classroomServices->classroomsInSemester($request->semester_id);

        return response([
            'data' => $classroom,
            'tree' => [$semester]
        ], 200);
    }

    public function store(CreateClassroomRequest $request)
    {
        $created = $this->classroomServices->store($request->input());

        if (!$created) {
            return response([
                'message' => 'Lớp học này đã tồn tại'
            ], 400);
        }

        return response([
            'message' => 'Tạo lớp học thành công'
        ], 201);
    }

    public function update(UpdateClassroomRequest $request)
    {
        $classroom = $request->get('classroom');

        $this->classroomServices->update($request->input(), $classroom);

        return response([
            'message' => 'Đã chuyển tất cả buổi học chưa diễn ra cho ' . $request->default_teacher_email
        ], 200);
    }

    public function destroy(Request $request)
    {
        $classroom = $request->get('classroom');

        $this->authorize('teacherOfClass', $classroom);

        $response = $this->classroomServices->destroy($classroom->id);

        return $response;
    }

    public function storeFeedback(StoreFeedbackRequest $request)
    {
        $classroom = $request->get('classroom');
        $response = $this->classroomServices->storeFeedback($request->input(), $classroom);

        return $response;
    }
}
