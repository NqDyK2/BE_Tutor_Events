<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\AttendanceServices;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    private $attendanceServices;

    public function __construct(AttendanceServices $attendanceServices)
    {
        $this->attendanceServices = $attendanceServices;
    }

    public function getListClass()
    {
        $classroom = $this->attendanceServices->getListClass();
        return response([
            'data' => $classroom
        ]);
    }

    public function attendanceDetail(Request $request)
    {
        $lesson = $request->get('lesson');
        $attendances = $this->attendanceServices->getDataByLesson($lesson);

        return response([
            'data' => $attendances,
            'lesson' => $lesson
        ], 200);
    }

    public function update(Request $request)
    {
        $response = $this->attendanceServices->update($request->lesson_id, $request->data);

        return $response;
    }

    public function studentCheckin(Request $request)
    {
        $lesson = $request->get('lesson');
        $response = $this->attendanceServices->studentCheckin($lesson);

        return $response;
    }
}
