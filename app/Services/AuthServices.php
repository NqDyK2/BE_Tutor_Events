<?php

namespace App\Services;

use App\Models\Classroom;
use App\Models\Lesson;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthServices
{
    public function loginGoogle($googleUser)
    {
        $user = User::where('email', $googleUser->email)->first();

        if (!$user) {
            $user = User::create([
                // 'code' => explode("@", $googleUser->email)[0],
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->avatar,
                'name' => $googleUser->name,
                'email' => $googleUser->email,
            ]);
        } elseif ($user->status == USER_STATUS_DEACTIVATE) {
            return response([
                'message' => 'This account has been blocked',
            ], 401);
        }

        return $user->createToken('API TOKEN')->plainTextToken;
    }

    public function getAuthDetail()
    {
        $auth = Auth::user()->only([
            'id',
            'name',
            'code',
            'email',
            'avatar',
            'role_id'
        ]);
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

        if ($auth['role_id'] == 1) {
            $auth['is_teacher'] = $isTeacher;
            return $auth;
        }

        if ($isTeacher) {
            $auth['role_id'] = 2;
            $auth['is_teacher'] = true;
            Auth::user()->update([
                'role_id' => 2
            ]);
            return $auth;
        }

        $isTutor = Lesson::join('classrooms', 'classrooms.id', 'lessons.classroom_id')
            ->join('semesters', 'semesters.id', 'classrooms.semester_id')
            ->where('semesters.end_time', '>=', now())
            ->where('lessons.tutor_email', $auth['email'])
            ->exists();

        if ($isTutor) {
            $auth['role_id'] = 4;
            Auth::user()->update([
                'role_id' => 2
            ]);
            return $auth;
        }

        Auth::user()->update([
            'role_id' => 3
        ]);

        return $auth;
    }
}
