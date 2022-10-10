<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\InsertExcel\InsertUserFromExcelJob;
use App\Services\ClassroomServices;
use App\Services\ExcelServices;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ExcelController extends Controller
{
    private $excelServices;

    public function __construct(ExcelServices $excelServices)
    {
        $this->excelServices = $excelServices;
    }

    public function import(Request $request)
    {
        $subject = $this->excelServices->requireSubjectImport($request->data);
        $classrooms = $this->excelServices->requireClassroomsImport($subject, $request->semester_id);
        $teachers = $this->excelServices->requireTeacherImport($request->data);

        foreach ($request->data as $x) {
            InsertUserFromExcelJob::dispatch($this->excelServices, [
                "data" => $x,
                "classrooms" => $classrooms,
                "teachers" => $teachers,
            ]);
        }

        return response([
            'message' => 'Import successfully'
        ], 201);
    }
}
