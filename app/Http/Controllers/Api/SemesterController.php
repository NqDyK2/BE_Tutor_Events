<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Semester\CreateSemesterRequest;
use App\Http\Requests\Semester\UpdateSemesterRequest;
use App\Services\SemesterServices;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    private $semesterServices;

    public function __construct(SemesterServices $semesterServices)
    {
        $this->semesterServices = $semesterServices;
    }

    public function index()
    {
        $semester = $this->semesterServices->getAll();

        return response([
            'semester' => $semester
        ],200);
    }

    public function store(CreateSemesterRequest $request)
    {
        $semester = $this->semesterServices->create($request->input());

        return response([
            'status' => true,
            'messege' => 'Semester create Successfully',
            'data' => $semester
        ],201);
    }

    public function show(Request $request)
    {
        $semester = $request->get('semester');
        
        return response([
            'status' => true,
            'data' => $semester
        ],201);
    }
    public function update(UpdateSemesterRequest $request)
    {
        $semester = $request->get('semester');
        $semesterUpdate = $this->semesterServices->update($request->input(),$semester);
        if($semesterUpdate)
        {
            return response([
                'status' => true,
                'massage' => 'Semester Update Successfully',
            ],201);
        }else {
            return response([
                'status' => false,
                'massage' => 'Update Semester False'
            ],400);
        }
    }

    public function destroy(Request $request)
    {
        $semester = $request->get('subject');
        $subjectDestroy = $this->subjectServices->destroy($semester);

        if($subjectDestroy){
            return response([
                'status' => true,
                'massage' => 'Semester Delete Successfully'
            ],201);
        }else {
            return response([
                'status' => false,
                'massage' => 'Delete false'
            ]);
        }

    }
}
