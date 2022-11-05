<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassStudent\ClassStudentRequest;
use App\Services\BreadcrumbServices;
use App\Services\ClassroomServices;
use App\Services\ClassStudentServices;
use Illuminate\Http\Request;

class ClassStudentController extends Controller
{
    private $classStudentServices;
    private $classroomServices;
    private $breadcrumbServices;

    public function __construct(
        ClassStudentServices $classStudentServices,
        ClassroomServices $classroomServices,
        BreadcrumbServices $breadcrumbServices
    ){
        $this->classStudentServices = $classStudentServices;
        $this->classroomServices = $classroomServices;
        $this->breadcrumbServices = $breadcrumbServices;
    }

    public function studentsInClassroom(Request $request)
    {
        $classroomId = $request->classroom_id;

        $students = $this->classStudentServices->classStudentsInClassroom($classroomId);
        $tree = $this->breadcrumbServices->getByClassroom($classroomId);

        return response([
            'data' => $students,
            'tree' => $tree
        ],200);
    }

    public function update(ClassStudentRequest $request)
    {
        $classStudent = $this->classStudentServices->store($request->input());
        return response([
            'message' => 'Thêm sinh viên thành công',
        ],201);
    }
}
