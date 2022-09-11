<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateClassStudentRequest;
use App\Services\ClassStudentServices;
use Illuminate\Http\Request;

class ClassStudentController extends Controller
{
    private $classStudentServices;

    public function __construct(ClassStudentServices $classStudentServices){
        $this->classStudentServices = $classStudentServices;
    }

    public function index()
    {
        $classStudent = $this->classStudentServices->index();
        return response([
            'data' => $classStudent
        ],200);
    }

    public function store(Request $request)
    {
        // dd($request);
        $classStudent = $this->classStudentServices->store($request->input());
        return response([
            'status' => true,
            'message' => 'Create Classroom successfully',
            'data' => $classStudent
        ],201);
    }

    public function show($id)
    {
        $classStudent = $this->classStudentServices->show($id);
        return response([
            'status' => true,
            'data' => $classStudent
        ],200);
    }

    public function update(Request $request, $id)
    {
        // // dd(Auth::user());
        // $classStudent = $request->get('classroom');
        // $this->authorize('updateClassroom', $classroom);

        $classStudent = $this->classStudentServices->update($request->input(), $id);
        if ($classStudent) {
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
        $checkDeleteclassStudent = $this->classStudentServices->destroy($id);

        if ($checkDeleteclassStudent) {
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
