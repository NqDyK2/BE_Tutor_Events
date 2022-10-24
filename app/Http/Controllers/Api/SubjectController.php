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

    public function __construct(SubjectServices $subjectServices)
    {
        $this->subjectServices = $subjectServices;

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

        if($subject)
        {
            return response([
                'massage' => 'Tạo mới môn học thành công',
            ],201);
        }else {
            return response([
                'massage' => 'Tạo mới môn học thất bại'
            ],400);
        }
    }

    public function show(Request $request)
    {
        $subject = $request->get('subject');

        return response([
            'data' => $subject
        ],201);
    }

    public function update(UpdateSubjectRequest $request)
    {
        $sub = $request->get('subject');
        $subject = $this->subjectServices->update($request->input(),$sub);
        if($subject)
        {
            return response([
                'massage' => 'Cập nhật môn học thành công',
            ],201);
        }else {
            return response([
                'massage' => 'Cập nhật môn học thất bại'
            ],400);
        }
    }

    public function destroy(Request $request)
    {
        $sub = $request->get('subject');
        $subject = $this->subjectServices->destroy($sub);

        if($subject){
            return response([
                'massage' => 'Subject Delete Successfully'
            ],201);
        }else {
            return response([
                'massage' => 'Delete false'
            ]);
        }

    }
}
