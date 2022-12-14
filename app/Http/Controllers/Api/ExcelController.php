<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\InsertExcel\InsertAllStudentAndResultJob;
use App\Jobs\InsertExcel\InsertWarningStudentFromExcelJob;
use App\Http\Services\ExcelServices;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExcelController extends Controller
{
    private $excelServices;

    public function __construct(ExcelServices $excelServices)
    {
        $this->excelServices = $excelServices;
    }

    public function importWarningStudents(Request $request)
    {
        $count = 0;
        $classrooms = $this->excelServices->getListRequireClassroom($request->semester_id, $request->data);

        foreach ($request->data as $x) {
            if (array_key_exists(Str::slug($x['subject']), $classrooms)) {
                $count++;
                $this->excelServices->updateWarningStudentsFile($x, $classrooms);
            }
        }

        return response([
            'message' => "Cập nhật danh sách sinh viên thành công"
        ], 200);
    }

    public function importAllStudentAndResult(Request $request)
    {
        $count = 0;
        $classrooms = $this->excelServices->getListRequireClassroom($request->semester_id, $request->data);

        foreach ($request->data as $x) {
            if (array_key_exists(Str::slug($x['subject']), $classrooms)) {
                $count++;
                $this->excelServices->updateAllStudentsFile($x, $classrooms);
            }
        }

        return response([
            'message' => "Cập nhật danh sách sinh viên thành công"
        ], 200);
    }
}
