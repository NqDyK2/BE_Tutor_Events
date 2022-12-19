<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subject\CreateSubjectRequest;
use App\Http\Requests\Subject\UpdateSubjectRequest;
use App\Http\Services\SubjectServices;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    private $subjectServices;

    public function __construct(SubjectServices $subjectServices)
    {
        $this->subjectServices = $subjectServices;
    }

    public function store(CreateSubjectRequest $request)
    {
        $this->subjectServices->create($request->input());

        return response([
            'message' => 'Tạo môn học thành công',
        ], 201);
    }

    public function update(UpdateSubjectRequest $request)
    {
        $subject  = $request->get('subject');

        $this->subjectServices->update($request->input(), $subject);

        return response([
            'message' => 'Cập nhật môn học thành công',
        ], 201);
    }

    public function destroy(Request $request)
    {
        $response = $this->subjectServices->destroy($request->subject_id);

        return $response;
    }
}
