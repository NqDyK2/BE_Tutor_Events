<?php
namespace App\Services;

use App\Models\Lesson;
use Illuminate\Support\Facades\DB;

class LessonServices
{
    public function lessonsInClassroom($id){
        $lesson = Lesson::select(
            'lessons.id',
            'subjects.name',
            'subjects.code',
            'lessons.start_time',
            'lessons.end_time',
            DB::raw('lessons.teacher_email as teacher_email'),
            DB::raw('lessons.tutor_email as tutor_email'),
            DB::raw('lessons.class_location_online'),
            DB::raw('lessons.class_location_offline'),
            'lessons.type',
            'lessons.classroom_id',
        )
        ->leftJoin('classrooms','classrooms.id','lessons.classroom_id')
        ->leftJoin('subjects','subjects.id','classrooms.subject_id')
        ->where('classroom_id', $id)
        ->orderBy('lessons.start_time','ASC','lessons.end_time','ASC')->get();
        return $lesson;
    }

    public function store($data)
    {
        return Lesson::create($data);
    }

    public function update($data, $lesson)
    {
        return $lesson->update($data);
    }
    public function destroy($lesson)
    {
        $lesson->delete();
        return $lesson->trashed();
    }

}
