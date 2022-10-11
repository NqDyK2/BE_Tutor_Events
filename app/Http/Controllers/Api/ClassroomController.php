<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Classroom\CreateClassroomRequest;
use App\Http\Requests\Classroom\UpdateClassroomRequest;
use App\Services\ClassroomServices;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    private $classroomServices;

    public function __construct(ClassroomServices $classroomServices){
        $this->classroomServices = $classroomServices;
    }

    public function classroomsInSemester($id)
{
        $classroom = $this->classroomServices->classroomsInSemester($id);

        return response([
            'data' => $classroom
        ],200);
    }

    public function store(CreateClassroomRequest $request)
    {
        $classroom = $this->classroomServices->store($request->input());
        
        if ($classroom) {
            return response([
                'data' => $classroom,
                'message' => 'Tạo mới lớp học thành công'
            ],201);
        } else {
            return response([
                'data' => $classroom,
                'message' => 'Tạo mới lớp học thất bại'
            ],500);
        }
    }

    public function update(UpdateClassroomRequest $request)
    {
        $classroom = $request->get('classroom');
        
        $this->authorize('checkOwnership', $classroom);

        $classroom = $this->classroomServices->update($request->input(), $classroom);

        if ($classroom) {
            return response([
                'data' => $classroom,
                'message' => 'Cập nhật lớp học thành công'
            ],200);
        } else {
            return response([
                'data' => $classroom,
                'message' => 'Cập nhật lớp học thất bại'
            ],500);
        }
    }

    public function destroy(Request $request)
    {
        $classroom = $request->get('classroom');
        
        $this->authorize('checkOwnership', $classroom);

        $checkDeleteClassroom = $this->classroomServices->isStarted($classroom->id);

        if ($checkDeleteClassroom) {
            return response([
                'message' => 'Lớp học này đã bắt đầu bạn không thể xóa'
            ],405);
        }

        $classroom = $this->classroomServices->destroy($classroom);

        if ($classroom) {
            return response([
                'message' => 'Xóa lớp học thành công'
            ],200);
        } else {
            return response([
                'message' => 'Xóa lớp học thất bại'
            ],500);
        }
    }

    // public function classroomsInUser($id){
    //     $classroom = $this->classroomServices->classroomsInUser($id);

    //     return response([
    //         'data' => $classroom
    //     ],200);
    // }
}
