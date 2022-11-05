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

    public function attendanceDetail(request $request)
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
        $updated = $this->attendanceServices->update($request->lesson_id, $request->data);

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
