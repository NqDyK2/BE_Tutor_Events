<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassroomRequest;
use App\Services\ClassroomServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ClassroomController extends Controller
{
    private $classroomServices;

    public function __construct(ClassroomServices $classroomServices){
        $this->classroomServices = $classroomServices;
    }

    public function index()
    {
        $Subject = $this->classroomServices->index();
        return response([
            '$Subject' => $Subject
        ],200);
    }

    public function store(ClassroomRequest $request)
    {
        $classroom = $this->classroomServices->store($request->input());
        return response([
            'status' => true,
            'message' => 'Create Classroom successfully',
            'data' => $classroom
        ],201);
    }

    public function show($id)
    {
        $subject = $this->classroomServices->show($id);
        return response([
            'status' => true,
            'data' => $subject
        ],200);
    }

    public function update(ClassroomRequest $request, $id)
    {
        $classroom = $this->classroomServices->update($request->input(), $id);
        if ($classroom) {
            return response([
                'message' => 'update Classroom successfully',
                'status' => true
            ],200);
        } else {
            return response([
                'message' => 'update Classroom failed',
                'status' => false
            ],400);
        }
    }

    public function destroy($id)
    {
        $checkDeleteSubject = $this->classroomServices->destroy($id);

        if ($checkDeleteSubject) {
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
