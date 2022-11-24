<?php

namespace App\Policies;

use App\Models\Lesson;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClassroomPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function before($auth, $user)
    {
        if ($auth->role_id == USER_ROLE_ADMIN) {
            return true;
        }
    }

    public function teacherOfClass($auth, $classroom, $lesson = null)
    {

        if ($auth->email == $classroom->default_teacher_email) {
            return true;
        }

        if (!empty($lesson)) {
            if ($lesson->teacher_email == $auth->email) {
                return true;
            }

            $isTeacherInclass = Lesson::where('classroom_id', $lesson->classroom_id)
            ->where('teacher_email', $auth->email)
            ->exists();

            if ($isTeacherInclass) {
                return true;
            }
        }

        return false;
    }
}
