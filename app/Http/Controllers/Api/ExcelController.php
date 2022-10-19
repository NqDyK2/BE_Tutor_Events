<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\InsertExcel\InsertUserFromExcelJob;
use App\Models\ClassStudent;
use App\Services\ExcelServices;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExcelController extends Controller
{
    private $excelServices;

    public function __construct(ExcelServices $excelServices)
    {
        $this->excelServices = $excelServices;
    }

    public function import(Request $request)
    {
        $classrooms = $this->excelServices->getListRequireClassroom($request->semester_id, $request->data);

        foreach ($request->data as $x) {
            if (array_key_exists(Str::slug($x['subject']), $classrooms)) {
                InsertUserFromExcelJob::dispatch($x, $classrooms);
                // ClassStudent::updateOrCreate([
                //     'student_email' => $x['student_email'],
                //     "classroom_id" => $classrooms[Str::slug($x['subject'])],
                // ], [
                //     "reason" => $x['reason'],
                // ]);
            }
        }

        return response([
            'message' => 'Cập nhật danh sách sinh viên thành công'
        ], 200);
    }
}
