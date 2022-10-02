<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Lesson;
use App\Models\Semester;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceServices
{
  public function getListClass()
  {
    $classroom = Classroom::select(
      'classrooms.id',
      DB::raw('users.name as teacher'),
      DB::raw('subjects.name as subject_name'),
      DB::raw('subjects.code as subject_code'),
    )
      ->whereHas('semester', function ($q) {
        return $q->where('end_time', '>', now())
          ->where('start_time', '<', now());
      })
      ->join('subjects', 'subjects.id', '=', 'classrooms.subject_id')
      ->leftJoin('users', 'users.id', '=', 'classrooms.user_id')
      ->with('lessons', function ($q) {
        return $q->select(
          'start_time',
          'end_time',
          'classroom_id',
        )
          ->where('start_time', '>', now())
          ->orderBy('start_time', 'ASC');
      })
      ->get();

    return ($classroom);
  }

  public function getListAttendance($classroom_id)
  {
    $getListStudent = Attendance::select(
      'attendances.id',
      'attendances.status',
      'attendances.user_id',
      DB::raw('users.name as user_name'),
      DB::raw('users.code as user_code'),
      'attendances.note',
    )
      ->whereHas('lesson', function ($q) use ($classroom_id) {
        return $q->where('classroom_id', $classroom_id)
          ->where('start_time', '<', now())
          ->where('end_time', '>', now());
      })

      ->leftJoin('users', 'users.id', '=', 'attendances.user_id')
      ->get();
    return $getListStudent;
  }

  public function update($classroom_id, $data)
  {
    $lesson = Lesson::where('classroom_id', $classroom_id)
    ->where('start_time', '<', now())
    ->where('end_time', '>', now())
    ->with('attendances', function ($q) {
      return $q->select('id', 'lesson_id');
    })
    ->first();

    if (!$lesson) {
      return false;
    }

    $ids = array_map(fn ($x) => $x['id'],$lesson->attendances->toArray());

    $presentIds = array_filter(array_map(fn ($x) => $x['status'] == ATTENDANCE_STATUS_PRESENT && in_array($x['id'], $ids) ? $x['id'] : null, $data));
    $absentIds = array_filter(array_map(fn ($x) => $x['status'] == ATTENDANCE_STATUS_ABSENT && in_array($x['id'], $ids) ? $x['id'] : null, $data));

    Attendance::whereIn('id', $absentIds)->update(["status" => false]);
    Attendance::whereIn('id', $presentIds)->update(["status" => true]);

    return true;
  }
}
