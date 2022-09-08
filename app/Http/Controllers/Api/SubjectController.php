<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subject\CreateSubjectRequest;
use App\Http\Requests\Subject\UpdateSubjectRequest;
use App\Services\SubjectServices;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    private $subjectServices;

    public function __construct(SubjectServices $subjectServices)
    {
        return $this->subjectServices = $subjectServices;
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

        if($subject)
        {
            return response([
                'status' => true,
                'massage' => 'Subject Update Successfully',
                'data' => $subject
            ],201);
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
