<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StatisticalServices;
use Illuminate\Http\Request;

class StatisticalController extends Controller
{
    private $statisticalServices;

    public function __construct(StatisticalServices $statisticalServices)
    {
        $this->statisticalServices = $statisticalServices;
    }

    public function index(Request $request)
    {
        $response = $this->statisticalServices->getSemesterStatisticalById($request->semester_id);

        return $response;
    }
}
