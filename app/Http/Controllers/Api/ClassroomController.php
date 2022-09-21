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

    public function index()
    {
        $classroom = $this->classroomServices->index();
        return response([
            '$classroom' => $classroom
        ],200);
    }

    public function store(CreateClassroomRequest $request)
    {
        $classroom = $this->classroomServices->store($request->input());
        return response([
            'status' => true,
            'message' => 'Create Classroom successfully',
            'data' => $classroom
        ],201);
    }

    public function show(Request $request)
    {
        $classroom = $request->get('classroom');
        
        return response([
            'status' => true,
            'data' => $classroom
        ],200);
    }

    public function update(UpdateClassroomRequest $request)
    {
        $classroom = $request->get('classroom');
        
        $this->authorize('checkOwnership', $classroom);

        $classroom = $this->classroomServices->update($request->input(), $classroom);

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

    public function destroy(Request $request)
    {
        $classroom = $request->get('classroom');
        
        $this->authorize('checkOwnership', $classroom);
        
        $checkDeleteClassroom = $this->classroomServices->destroy($classroom);

        if ($checkDeleteClassroom) {
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
