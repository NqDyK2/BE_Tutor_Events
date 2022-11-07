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
        $tree[] = Semester::select(
            'id',
            'name',
        )
        ->whereHas('classrooms', function($q) use ($classroomId) {
            $q->where('id', $classroomId);
        })
        ->first();
        $tree[] = Classroom::select(
            'id',
            'name',
        )
        ->where('id', $classroomId)
        ->first();

        return $tree;
    }

    public function getByLesson($lesson)
    {
        $lessonId = $lesson->id;
        $tree = [];

        $tree[] = Semester::select(
            'id',
            'name',
        )
        ->whereHas('lessons', function($q) use ($lessonId) {
            $q->where('id', $lessonId);
        })
        ->first();

        $tree[] = Classroom::select(
            'id',
            'name',
        )
        ->whereHas('lessons', function($q) use ($lessonId) {
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
