<?php

namespace App\Policies;

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

    public function teacherOfClass($auth, $classroom)
    {
        if ($auth->email == $classroom->default_teacher_email) {
            return true;
        }
        return false;
    }
}
