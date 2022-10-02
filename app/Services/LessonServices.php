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
            DB::raw('users.code as teacher'),
            DB::raw('lessons.tutor_email as tutor'),
            DB::raw('lessons.class_location_online'),
            DB::raw('lessons.class_location_offline'),
        )
        ->leftJoin('classrooms','classrooms.id','lessons.classroom_id')
        ->leftJoin('subjects','subjects.id','classrooms.subject_id')
        ->leftJoin('users','users.id','classrooms.user_id')
        ->where('classroom_id', $classroom_id)
        ->orderBy('lessons.start_time','ASC','lessons.end_time','ASC')->get();
        return $lesson;
    }

    public function store($data)
    {
        $lesson = Lesson::create($data);
        return $lesson->only(
            [
                'classroom_id',
                'type',
                'start_time',
                'end_time',
                'class_location_online',
                'class_location_offline',
                'tutor_email',
                'document_path',
            ]
        );
    }

    public function update($data, $lesson)
    {
        return $lesson->update($data);
    }

    public function show($lesson)
    {
        return $lesson->only(
            [
                'classroom_id',
                'type',
                'start_time',
                'end_time',
                'class_location_online',
                'class_location_offline',
                'tutor_email',
                'document_path',
            ]
        );
    }

    public function destroy($lesson)
    {
        $lesson->delete();
        return $lesson->trashed();
    }

}
