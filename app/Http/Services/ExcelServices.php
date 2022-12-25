<?php

namespace App\Http\Services;

use App\Models\Classroom;
use App\Models\ClassStudent;
use App\Models\User;
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

    public function updateAllStudentsFile($user, $classrooms)
    {
        $finalStatusList = [
            ClassStudent::FINAL_RESULT_PASSED,
            ClassStudent::FINAL_RESULT_NOT_PASSED,
            ClassStudent::FINAL_RESULT_BANNED,
        ];

        try {
            $dataUpdate = [
                $user['student_code'],
            ];

            if (in_array($user['final_result'], $finalStatusList)) {
                $dataUpdate['final_result'] = (int)$user['final_result'];
                if ($user['final_result'] > ClassStudent::FINAL_RESULT_BANNED) {
                    $dataUpdate['final_score'] = (float)$user['final_score'];
                }
            }

            User::updateOrCreate([
                'email' => $user['student_code'] . '@fpt.edu.vn',
            ], [
                'code' => $user['student_code'],
                'name' => $user['student_code'],
            ]);

            ClassStudent::updateOrCreate([
                'student_email' => $user['student_code'] . '@fpt.edu.vn',
                'classroom_id' => $classrooms[Str::slug($user['subject'])],
            ], $dataUpdate);
        } catch (\Throwable $th) {
            logger($th);
        }
    }

    public function updateWarningStudentsFile($user, $classrooms)
    {
        try {
            User::updateOrCreate([
                'email' => $user['student_email'],
            ], [
                'code' => $user['student_code'],
                'name' => $user['student_name'],
                'phone_number' => $user['student_phone'],
            ]);

            ClassStudent::updateOrCreate([
                'student_email' => $user['student_email'],
                'classroom_id' => $classrooms[Str::slug($user['subject'])],
            ], [
                "reason" => $user['reason'],
                "is_warning" => true,
            ]);
        } catch (\Throwable $th) {
            logger($th);
        }
    }
}
