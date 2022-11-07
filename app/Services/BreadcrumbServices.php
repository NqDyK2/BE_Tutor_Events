<?php

namespace App\Services;

use App\Models\Classroom;
use App\Models\Semester;
use Illuminate\Support\Facades\DB;

class BreadcrumbServices
{
    public function getByClassroom($classroomId)
    {
        return Semester::select(
            'id',
            'name',
        )
        ->whereHas('classrooms', function($q) use ($classroomId) {
            $q->where('id', $classroomId);
        })
        ->with('classrooms', function($q) use ($classroomId) {
            $q->select(
                'classrooms.id',
                'subjects.name',
                'subjects.code',
                'subjects.id',
                'classrooms.subject_id',
                'classrooms.semester_id'
            )
            ->where('classrooms.id', $classroomId)
            ->join('subjects', 'subjects.id', '=', 'classrooms.subject_id');
        })
        ->first();
    }

    public function getByLesson($lesson)
    {
        $lessonId = $lesson->id;
        $tree =  Semester::select(
            'id',
            'name',
        )
        ->whereHas('lessons', function($q) use ($lessonId) {
            $q;
        })
        ->with('classrooms', function($q) use ($lessonId) {
            $q->select(
                'classrooms.id',
                'subjects.name',
                'subjects.code',
                'subjects.id',
                'classrooms.subject_id',
                'classrooms.semester_id'
            )
            ->whereHas('lessons', function($q) use ($lessonId) {
                $q->where('id', $lessonId);
            })
            ->with('lessons', function($q) use ($lessonId) {
                $q->where('id', $lessonId);
            })
            ->join('subjects', 'subjects.id', '=', 'classrooms.subject_id');
        })
        ->first();
        $tree->classrooms[0]->lessons[0] = $lesson;
        return $tree;
    }
}
