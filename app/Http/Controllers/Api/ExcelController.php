<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\InsertExcel\InsertUserFromExcelJob;
use App\Models\ClassStudent;
use App\Models\User;
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
        $count = 0;
        $classrooms = $this->excelServices->getListRequireClassroom($request->semester_id, $request->data);

        foreach ($request->data as $x) {
            if (array_key_exists(Str::slug($x['subject']), $classrooms)) {
                $count++;
                InsertUserFromExcelJob::dispatch($x, $classrooms);
            }
        }

        return response([
            'message' => "Cập nhật thành công " . $count . " bản ghi"
        ], 200);
    }
}
