<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Lesson;

class AttendanceServices
{
  public function getListClass()
  {
    $curentDate = date('Y-m-d');
    $lessons = Lesson::whereBetween('start_time', [$curentDate.' 00:00:00', $curentDate.' 23:59:59'])
    ->with('classroom')
    ->get();

    return $lessons;
  }

  public function getListStudent($lesson_id)
  {
    $getListStudent = Attendance::where('lesson_id', $lesson_id)
    ->with('user')
    ->get();
    return $getListStudent;
  }

  public function update($lesson_id, $data)
  {
    $presentIds = array_filter(array_map( fn($x) => $x['status']==1 ? $x['id'] : null, $data ));
    $absentIds = array_filter(array_map( fn($x) => $x['status']==0 ? $x['id'] : null, $data ));

    Attendance::whereIn('id', $absentIds)->update(["status" => true]);
    Attendance::whereIn('id', $presentIds)->update(["status" => false]);
  }
}
