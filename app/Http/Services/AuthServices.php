<?php

namespace App\Http\Services;

use App\Models\Classroom;
use App\Models\Lesson;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthServices
{
    public function loginGoogle($googleUser)
    {
        $acceptMailDomains = [
            "fpt.edu.vn",
            "fe.edu.vn",
        ];

        if (!in_array(explode("@", $googleUser->email)[1], $acceptMailDomains)) {
            return response([
                'message' => 'Hãy đăng nhập với mail "fpt.edu.vn"',
            ], 401);
        }

        if ($googleUser->email) {
            $user = User::where('email', $googleUser->email)->first();
        }

        if (!$user) {
            $user = User::create([
                'code' => explode("@", $googleUser->email)[0],
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->avatar,
                'name' => $googleUser->name,
                'email' => $googleUser->email,
            ]);
        } elseif ($user->status == User::STATUS_DEACTIVATE) {
            return response([
                'message' => 'This account has been blocked',
            ], 401);
        }

        return response([
            'token' => $user->createToken('API TOKEN')->plainTextToken
        ], 200);
    }

    public function getAuthDetail()
    {
        $auth = Auth::user()->only(['id', 'name', 'code', 'email', 'avatar', 'role_id']);
        $auth['is_teacher'] = false;

        $isTeacher = Classroom::join('semesters', 'semesters.id', 'classrooms.semester_id')
            ->where('semesters.end_time', '>=', now())
            ->where('classrooms.default_teacher_email', $auth['email'])
            ->exists();

        if (!$isTeacher) {
            $isTeacher = Semester::
            where('semesters.end_time', '>=', now())
            ->whereHas('lessons', function ($q) {
                return $q->where('teacher_email', Auth::user()->email);
            })
            ->exists();
        }

        if ($auth['role_id'] == User::ROLE_ADMIN) {
            $auth['is_teacher'] = $isTeacher;
            return $auth;
        }

        if ($isTeacher) {
            if ($auth['role_id'] != User::ROLE_TEACHER) {
                $auth['role_id'] = User::ROLE_TEACHER;
                Auth::user()->update(['role_id' => User::ROLE_TEACHER]);
            }
            $auth['is_teacher'] = true;
            return $auth;
        }

        if ($auth['role_id'] != User::ROLE_STUDENT) {
            Auth::user()->update(['role_id' => User::ROLE_STUDENT]);
        }

        $isTutor = Lesson::join('classrooms', 'classrooms.id', 'lessons.classroom_id')
            ->join('semesters', 'semesters.id', 'classrooms.semester_id')
            ->where('semesters.end_time', '>=', now())
            ->where('lessons.tutor_email', $auth['email'])
            ->exists();

        if ($isTutor) {
            $auth['role_id'] = User::ROLE_TUTOR;
            return $auth;
        }

        $auth['role_id'] = User::ROLE_STUDENT;

        return $auth;
    }
}
