<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subject\CreateSubjectRequest;
use App\Http\Requests\Subject\UpdateSubjectRequest;
use App\Services\SubjectServices;
use App\Http\Controllers\Api\MajorController;
use App\Services\MajorServices;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    private $subjectServices;
    private $majorServices;

    public function __construct(SubjectServices $subjectServices , MajorServices $majorServices)
    {
        $this->subjectServices = $subjectServices;
        $this->majorServices = $majorServices;

    }

    public function index()
    {
        $subject = $this->subjectServices->getAll();
        return response([
            'subject' => $subject
        ],200);
    }

    public function store(CreateSubjectRequest $request)
    {
        $subject = $this->subjectServices->create($request->input());

        return response([
            'status' => true,
            'massage' => 'Subject create Successfully',
            'data' => $subject
        ],201);
    }

    public function show($id)
    {
        $subject = $this->subjectServices->show($id);

        return response([
            'status' => true ,
            'data' => $subject
        ],201);
    }

    public function update(UpdateSubjectRequest $request, $id)
    {
        $subject = $this->subjectServices->update($request->input(),$id);
        $major = $this->majorServices->getAll();
        $checkMajorId = $request->input('major_id');
        json_decode($major, true);
        if($subject)
        {
            foreach($major as $val){
                if($val->id == $checkMajorId)
                {
                    return response([
                        'status' => true,
                        'massage' => 'Subject Update Successfully',
                    ],201);
                }
            }
            return response([
                'status' => false,
                'massage' => 'Major_id does not exist',
            ],404);
        }else {
            return response([
                'status' => false,
                'massage' => 'Update Subject False'
            ],400);
        }
    }

    public function destroy($id)
    {
        $subject = $this->subjectServices->destroy($id);

        if($subject){
            return response([
                'status' => true,
                'massage' => 'Subject Delete Successfully'
            ],201);
        }else {
            return response([
                'status' => false,
                'massage' => 'Delete false'
            ]);
        }

    }
}
