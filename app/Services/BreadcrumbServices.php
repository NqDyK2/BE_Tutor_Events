<?php

namespace App\Services;

use App\Models\Classroom;
use App\Models\Semester;
use Illuminate\Support\Facades\DB;

class BreadcrumbServices
{
    public function getByClassroom($classroomId)
    {
        $tree = [];
        $tree[] = Semester::whereHas('classrooms', function ($q) use ($classroomId) {
            $q->where('id', $classroomId);
        })
            ->first();
        $tree[] = Classroom::select(
            'classrooms.id',
            DB::raw('subjects.name as name'),
            'subjects.code',
            DB::raw('subjects.id as subject_id'),
        )
            ->join('subjects', 'subjects.id', '=', 'classrooms.subject_id')
            ->where('classrooms.id', $classroomId)
            ->first();

        return $tree;
    }

    public function getByLesson($lesson)
    {
        $lessonId = $lesson->id;
        $tree = [];

        $tree[] = Semester::whereHas('lessons', function ($q) use ($lessonId) {
            $q->where('id', $lessonId);
        })
            ->first();

        $tree[] = Classroom::select(
            DB::raw('subjects.id as subject_id'),

            DB::raw('subjects.name as name'),
            'subjects.code',
            'classrooms.id',
            'classrooms.subject_id',
        )
            ->join('subjects', 'subjects.id', '=', 'classrooms.subject_id')
            ->whereHas('lessons', function ($q) use ($lessonId) {
                $q->where('id', $lessonId);
            })
            ->first();

        $tree[] = $lesson->only(
            'id',
            'name'
        );

        return $tree;
    }
}
