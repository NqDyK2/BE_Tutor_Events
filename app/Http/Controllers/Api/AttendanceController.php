<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AttendanceServices;
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

    public function getListAttendance(Request $request)
    {
        $attendances = $this->attendanceServices->getListAttendance($request->classroom_id);

        if (count($attendances) == 0) {
            return response ([
                'message' => 'Chưa đến thời gian điểm danh'
            ], 400);
        }

        return response([
            'data' => $attendances
        ], 200);
    }

    public function update(Request $request)
    {
        $updated = $this->attendanceServices->update($request->classroom_id, $request->data);

        if (!$updated) {
            return response([
                'message' => 'Chưa đến thời gian điểm danh'
            ], 400);
        }
        
        return response([
            'message' => 'Cập nhật điểm danh thành công'
        ], 200);
    }
}
