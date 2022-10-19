<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassStudent\ClassStudentRequest;
use App\Services\ClassroomServices;
use App\Services\ClassStudentServices;

class ClassStudentController extends Controller
{
    private $classStudentServices;
    private $classroomServices;
    public function __construct(
        ClassStudentServices $classStudentServices,
        ClassroomServices $classroomServices
    ){
        $this->classStudentServices = $classStudentServices;
        $this->classroomServices = $classroomServices;
    }

    public function studentsInClassroom($classroom_id)
    {   
        $students = $this->classStudentServices->classStudentsInClassroom($classroom_id);
        return response([
            'data' => $students
        ],200);
    }

    public function update(ClassStudentRequest $request)
    {
        $classStudent = $this->classStudentServices->store($request->input());
        return response([
            'message' => 'Create Classroom successfully',
        ],201);
    }
}
