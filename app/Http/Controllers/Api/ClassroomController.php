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
                'message' => 'Create Classroom successfully'
            ],201);
        } else {
            return response([
                'data' => $classroom,
                'message' => 'Create Classroom failed'
            ],500);
        }
    }

    public function show(Request $request)
    {
        $classroom = $request->get('classroom');
        
        return response([
            'data' => $classroom,
            'messages' => 'Show Classroom successfully'
        ],200);
    }

    public function update(UpdateClassroomRequest $request)
    {
        $classroom = $request->get('classroom');
        
        $this->authorize('checkOwnership', $classroom);

        $classroom = $this->classroomServices->update($request->input(), $classroom);

        if ($classroom) {
            return response([
                'data' => $classroom,
                'message' => 'update Classroom successfully'
            ],200);
        } else {
            return response([
                'data' => $classroom,
                'message' => 'update Classroom failed'
            ],500);
        }
    }

    public function destroy(Request $request)
    {
        $classroom = $request->get('classroom');
        
        $this->authorize('checkOwnership', $classroom);
        
        $checkDeleteClassroom = $this->classroomServices->destroy($classroom);

        if ($checkDeleteClassroom) {
            return response([
                'message' => 'delete classroom successfully'
            ],200);
        } else {
            return response([
                'message' => 'delete classroom failed'
            ],500);
        }
    }
}
