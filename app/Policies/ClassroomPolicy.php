<?php

namespace App\Policies;

use App\Models\User;
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

    public function updateClassroom($auth, $classroom)
    {
        if ($auth->id == $classroom->user_id) {
            return true;
        }
        return false;
    }
}
