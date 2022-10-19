<?php

namespace App\Services;

use App\Models\Classroom;
use App\Models\Major;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Str;

class ExcelServices
{
    //Trả về các bản ghi Major cần thiết, nếu bản ghi chưa có sẽ tự động tạo
    public function requireMajorImport($data)
    {
        $majors = [];
        $requireMajors = [];

        //Lấy danh sách majors mà data cần
        $requireMajors = array_unique(array_map(function ($x) {
            return $x['major'];
        }, $data));

        //them slug cua major
        $requireMajors = array_map(function ($x) {
            return [
                "name" => $x,
                "slug" => Str::slug($x)
            ];
        }, $requireMajors);

        //tao cac major con thieu va tra ve mang majors lay tu database
        foreach ($requireMajors as $rm) {
            $majors[$rm['slug']] = Major::firstOrCreate(
                ['slug' => $rm['slug']],
                ['name' => $rm['name']]
            )->id;
        }
        return $majors;
    }

    //Trả về các bản ghi Subject cần thiết, nếu bản ghi chưa có sẽ tự động tạo
    public function requireSubjectImport($data)
    {
        $subjects = [];
        $requireSubjects = [];
        $majors = $this->requireMajorImport($data);

        //lay cac subject can thiet trong data
        $requireSubjects = array_unique(array_map(function ($x) {
            return Str::slug($x['major']) . "&|&" . Str::slug($x['subject']) . "&|&" . $x['subject'];
        }, $data));

        //them slug cua subject
        foreach ($requireSubjects as $i => $rs) {
            $sbj = explode("&|&", $rs);
            $requireSubjects[$i] = [
                "major_id" => $majors[$sbj[0]],
                "slug" => $sbj[1],
                "code" => $sbj[1],
                "name" => $sbj[2],
            ];
        }

        //Tao cac subject con thieu va tra ve mang subjects lay tu database
        foreach ($requireSubjects as $rs) {
            $subjects[$rs['slug']] = Subject::firstOrCreate(
                [
                    'slug' => $rs['slug']
                ],
                [
                    'code' => $rs['slug'],
                    'name' => $rs['name'],
                    'major_id' => $rs['major_id']
                ]
            )->id;
        }
        return $subjects;
    }

    //Tao cac classroom con thieu va tra ve mang classrooms lay tu database
    public function requireClassroomsImport(array $subjectIds, $semesterId)
    {
        $classrooms = [];

        foreach ($subjectIds as $name => $subject_id) {
            $classrooms[$name] = Classroom::firstOrCreate(
                [
                    'semester_id' => $semesterId,
                    'subject_id' => $subject_id,
                ],
                [
                    'name' => $name,
                ]
            )->id;
        }

        return $classrooms;
    }

    public function getListRequireClassroom($semesterId, $data)
    {
        $classrooms = [];

        $requireSubjects = array_unique(array_map(function ($x) {
            return Str::slug($x['subject']);
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
            $classrooms[$cs->code] = $cs->id;
        }

        return $classrooms;
    }
}
