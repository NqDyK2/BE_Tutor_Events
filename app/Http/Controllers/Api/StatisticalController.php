<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Statistical\getUserStatisticalRequest;
use App\Http\Services\StatisticalServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StatisticalController extends Controller
{
    private $statisticalServices;

    public function __construct(StatisticalServices $statisticalServices)
    {
        $this->statisticalServices = $statisticalServices;
    }

    public function getSemesterStatistical(Request $request)
    {
        $response = $this->statisticalServices->getSemesterStatistical($request->semester_id);

        return $response;
    }

    public function getUserStatisticalInSemester(getUserStatisticalRequest $request)
    {
        $data = $this->statisticalServices->getTeacherStatistical($request->semester_id, $request->email, $request->role);

        return response([
            "data" => $data
        ], 200);
    }

    public function getExportData(Request $request)
    {
        $semester = $request->get('semester');
        $response = $this->statisticalServices->getSemesterExportData($semester->id);

        return $response;
    }
}
