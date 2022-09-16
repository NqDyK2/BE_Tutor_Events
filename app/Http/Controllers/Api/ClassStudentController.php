<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassStudentRequest;
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

    public function index()
    {
        $classStudent = $this->classStudentServices->index();
        return response([
            'data' => $classStudent
        ],200);
    }

    public function store(ClassStudentRequest $request)
    {
        $classStudent = $this->classStudentServices->store($request->input());
        return response([
            'status' => true,
            'message' => 'Create Classroom successfully',
            'data' => $classStudent
        ],201);
    }

    public function destroy($id)
    {
        $classStudent = $this->classStudentServices->show($id);
        $this->authorize('updateClassroom', $classStudent->classroom);
        $isStarted = $this->classroomServices->isStarted($classStudent->classroom->id);
        if ($isStarted) {
            return response([
                'message' => 'you cannot delete this record',
                'status' => false
            ],200);
        }
        $checkDeleteClassStudent = $this->classStudentServices->destroy($id);

        if ($checkDeleteClassStudent) {
            return response([
                'message' => 'delete classroom successfully',
                'status' => true
            ],200);
        } else {
            return response([
                'message' => 'delete classroom failed',
                'status' => false
            ],400);
        }
    }
}
