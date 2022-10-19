<?php

namespace App\Services;

use App\Models\Lesson;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthServices
{
    public function loginGoogle($googleUser)
    {
        $user = User::where('email', $googleUser->email)->first();

        if (!$user) {
            $user = User::create([
                'code' => explode("@", $googleUser->email)[0],
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->avatar,
                'name' => $googleUser->name,
                'email' => $googleUser->email,
            ]);
        } elseif ($user->status == USER_STATUS_DEACTIVATE) {
            return response([
                'message' => 'This account has been blocked',
            ], 403);
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
        if ($auth['role_id'] == 1) return $auth;

        $isTeacher = Lesson::join('classrooms', 'classrooms.id', 'lessons.classroom_id')
            ->join('semesters', 'semesters.id', 'classrooms.semester_id')
            ->where('semesters.start_time', '<=', now())->where('semesters.end_time', '>=', now())
            ->where('lessons.teacher_email', $auth['email'])
            ->exists();


        if ($isTeacher) {
            $auth['role_id'] = 2;
            return $auth;
        }

        $isTutor = Lesson::join('classrooms', 'classrooms.id', 'lessons.classroom_id')
            ->join('semesters', 'semesters.id', 'classrooms.semester_id')
            ->where('semesters.start_time', '<=', now())->where('semesters.end_time', '>=', now())
            ->where('lessons.tutor_email', $auth['email'])
            ->exists();

        if ($isTutor) {
            $auth['role_id'] = 4;
            return $auth;
        }

        return $auth;
    }
}
