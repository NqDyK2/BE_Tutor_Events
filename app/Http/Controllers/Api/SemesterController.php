<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Semester\CreateSemesterRequest;
use App\Http\Requests\Semester\UpdateSemesterRequest;
use App\Services\ExcelServices;
use App\Services\SemesterServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class SemesterController extends Controller
{
    private $semesterServices;

    public function __construct(SemesterServices $semesterServices,)
    {
        $this->semesterServices = $semesterServices;
    }

    public function index()
    {
        $semester = $this->semesterServices->getAll();
        return response([
            'data' => $semester
        ],200);
    }

    public function store(CreateSemesterRequest $request)
    {
        $semester = $this->semesterServices->create($request->input());
        return response([
            'message' => 'Tạo kỳ học thành công'
        ],201);
    }

    public function update(UpdateSemesterRequest $request)
    {
        $semester = $request->get('semester');
        $this->semesterServices->update($request->input(),$semester);

        return response([
            'massage' => 'Cập nhật kỳ học thành công',
        ],200);
    }

    public function destroy(Request $request)
    {
        $response = $this->semesterServices->destroy($request->semester_id);

        return $response;
    }
}
