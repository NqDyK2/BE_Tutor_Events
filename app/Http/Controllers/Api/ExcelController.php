<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\InsertExcel\InsertUserFromExcelJob;
use App\Jobs\InsertExcel\SendMailInsertJob;
use App\Models\ClassStudent;
use App\Services\ExcelServices;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\MailServices;

class ExcelController extends Controller
{
    private $excelServices;
    private $mailService;

    public function __construct(ExcelServices $excelServices, MailServices $mailService)
    {
        $this->excelServices = $excelServices;
        $this->mailService = $mailService;
    }

    public function import(Request $request)
    {
        $count = 0;
        $classrooms = $this->excelServices->getListRequireClassroom($request->semester_id, $request->data);

        foreach ($request->data as $x) {
            if (array_key_exists(Str::slug($x['subject']), $classrooms)) {
                ++$count;
                $classStudent = ClassStudent::where('student_email',$x['student_email'])
                ->where('classroom_id',$classrooms[Str::slug($x['subject'])])
                ->exists();
                InsertUserFromExcelJob::dispatch($x, $classrooms);
                if (!$classStudent) {
                    SendMailInsertJob::dispatch($x, $this->mailService);
                }
            }
        }

        return response([
            'message' => 'Cập nhật thành công ' . $count . ' bản ghi'
        ], 200);
    }
}
