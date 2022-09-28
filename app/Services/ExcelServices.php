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
    public function requireMajorImport($data)
    {
        $majors = [];
        $requireMajors = [];

        //lay cac major can thiet trong data
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

        //tao cac major con thieu va tra ve mang major
        foreach ($requireMajors as $rm) {
            $majors[$rm['slug']] = Major::firstOrCreate(
                ['slug' => $rm['slug']],
                ['name' => $rm['name']]
            )->id;
        }
        return $majors;
    }

    //tra ve mang cac subject can thiet trong data
    public function requireSubjectImport($data)
    {
        $subjects = [];
        $requireSubjects = [];
        $majors = $this->requireMajorImport($data);

        //lay cac subject can thiet trong data
        $requireSubjects = array_unique(array_map(function ($x) {
            return Str::slug($x['major']) . "&|&" . Str::slug($x['subject']) . "&|&" . $x['subject'];
        }, $data));

        foreach ($requireSubjects as $i => $rs) {
            $sbj = explode("&|&", $rs);
            $requireSubjects[$i] = [
                "major_id" => $majors[$sbj[0]],
                "slug" => $sbj[1],
                "name" => $sbj[2],
            ];
        }

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

    public function requireTeacherImport($data)
    {
        $teachers = [];
        $requireTeachers = [];

        //lay cac major can thiet trong data
        $requireTeachers = array_unique(array_map(function ($x) {
            return Str::slug($x['school_teacher_code']) . "&|&" . $x['school_teacher_name'];
        }, $data));

        foreach ($requireTeachers as $i => $rt) {
            $tch = explode("&|&", $rt);
            $requireTeachers[$i] = [
                "code" => $tch[0],
                "name" => $tch[1],
            ];
        }

        //tao cac major con thieu va tra ve mang major
        foreach ($requireTeachers as $rt) {
            $teachers[$rt['code']] = SchoolTeacher::firstOrCreate(
                ['code' => $rt['code']],
                ['name' => $rt['name']]
            )->id;
        }
        return $teachers;
    }

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
                    'user_id' => Auth::id()
                ]
            )->id;
        }

        return $classrooms;
    }

    public function requireUserImport($data)
    {
        try {
            $user = User::firstOrCreate(
                [
                    'email' => $data['student_email'],
                ],
                [
                    'name' => $data['student_name'],
                    'user_code' => $data['student_code'],
                    'phone_number' => $data['student_phone'],
                ]
            )->id;
        } catch (\Throwable $th) {
        }

        return $user;
    }

    public function requireUserClassroom($data)
    {
        ClassStudent::updateOrCreate($data[0], $data[1]);
    }

}
