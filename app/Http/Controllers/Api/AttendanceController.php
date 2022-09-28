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

    public function getListLesson()
    {
        $classroom = $this->attendanceServices->getListClass();
        return response([
            'data' => $classroom
        ]);
    }

    public function getListStudent(Request $request)
    {
        $students = $this->attendanceServices->getListStudent($request->lesson_id);
        return response([
            'data' => $students
        ]);
    }

    public function update(Request $request)
    {
        $updated = $this->attendanceServices->update($request->id, $request->data);
        return response([
            'message' => 'Update attendance successfully'
        ]);
    }
}
