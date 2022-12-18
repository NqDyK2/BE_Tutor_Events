<?php

namespace App\Http\Services;

use App\Models\Classroom;
use Illuminate\Support\Str;

class ExcelServices
{
    public function getListRequireClassroom($semesterId, $data)
    {
        $classrooms = [];

        $requireSubjects = array_unique(array_map(function ($x) {
            return strtoupper(Str::slug($x['subject']));
        }, $data));


        $classroomsSelected = Classroom::select(
            'classrooms.id',
            'subjects.code',
            'classrooms.semester_id'
        )
        ->join('subjects', 'subjects.id', 'classrooms.subject_id')
        ->where('classrooms.semester_id', $semesterId)
        ->whereIn('subjects.code', $requireSubjects)
        ->get();
        
        foreach ($classroomsSelected as $cs) {
            $classrooms[Str::slug($cs->code)] = $cs->id;
        }
        
        return $classrooms;
    }
}
