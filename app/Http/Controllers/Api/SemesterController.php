<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Semester\CreateSemesterRequest;
use App\Http\Requests\Semester\UpdateSemesterRequest;
use App\Http\Services\SemesterServices;
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
            'data' => $semester
        ], 200);
    }

    public function store(CreateSemesterRequest $request)
    {
        $semester = $this->semesterServices->create($request->input());
        return response([
            'message' => 'Tạo kỳ học thành công'
        ], 201);
    }

    public function update(UpdateSemesterRequest $request)
    {
        $semester = $request->get('semester');
        $this->semesterServices->update($request->input(), $semester);

        return response([
            'message' => 'Cập nhật kỳ học thành công',
        ], 200);
    }

    public function destroy(Request $request)
    {
        $response = $this->semesterServices->destroy($request->semester_id);

        return $response;
    }
}
