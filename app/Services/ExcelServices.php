<?php

namespace App\Services;

use App\Models\Classroom;
use App\Models\ClassStudent;
use App\Models\Major;
use App\Models\SchoolTeacher;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
                    'name' => $rs['name'],
                    'major_id' => $rs['major_id']
                ]
            )->id;
        }
        return $subjects;
    }

    //Tạo các subject c
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

    public function requireUserImport($data)
    {
            $user = User::firstOrCreate(
                [
                    'email' => $data['student_email'],
                    'code' => $data['student_code'],
                ],
                [
                    'name' => $data['student_name'],
                    'phone_number' => $data['student_phone'],
                ]
            )->id;

        return $user;
    }
}
