<?php
namespace App\Services;

use App\Models\Lesson;
use Illuminate\Support\Facades\DB;

class LessonServices
{
    public function index($classroom_id){
        $lesson = Lesson::select(
            'lessons.id',
            'lessons.classroom_id',
            'lessons.type',
            'lessons.start_time',
            'lessons.end_time',
            'subjects.name',
            'subjects.code',
            DB::raw('users.user_code as teacher'),
            DB::raw('lessons.tutor_email as tutor'),
            DB::raw('lessons.class_location_online'),
            DB::raw('lessons.class_location_offline'),
        )
        ->leftJoin('classrooms','classrooms.id','lessons.classroom_id')
        ->leftJoin('subjects','subjects.id','classrooms.subject_id')
        ->leftJoin('users','users.id','classrooms.user_id')
        ->where('classroom_id', $classroom_id)
        ->orderBy('lessons.start_time','ASC','lessons.end_time','ASC');
        return $lesson;
    }

    public function store($data)
    {
        $lesson = Lesson::create($data);
        $data = [
            'classroom_id' => $lesson->classroom_id,
            'type' => $lesson->type,
            'start_time' => $lesson->start_time,
            'end_time' => $lesson->end_time,
            'class_location_online' => $lesson->class_location_online,
            'class_location_offline' => $lesson->class_location_offline,
            'tutor_email' => $lesson->tutor_email,
        ];
        return $data;
    }

    public function update($data, $lesson)
    {
        return $lesson->update($data);
    }

    public function show($lesson)
    {
        $data = [
            'classroom_id' => $lesson->classroom_id,
            'type' => $lesson->type,
            'start_time' => $lesson->start_time,
            'end_time' => $lesson->end_time,
            'class_location_online' => $lesson->class_location_online,
            'class_location_offline' => $lesson->class_location_offline,
            'tutor_email' => $lesson->tutor_email,
        ];
        return $data;
    }

    public function destroy($lesson)
    {
        $lesson->delete();
        return $lesson->trashed();
    }

}
