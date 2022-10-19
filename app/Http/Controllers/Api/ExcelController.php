<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\InsertExcel\InsertUserFromExcelJob;
use App\Services\ExcelServices;
use Illuminate\Http\Request;

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

        foreach ($request->data as $x) {
            InsertUserFromExcelJob::dispatch($this->excelServices, [
                "data" => $x,
                "classrooms" => $classrooms,
            ]);
        }

        return response([
            'message' => 'Import successfully'
        ], 200);
    }
}
