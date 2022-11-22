<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function before($auth, $user)
    {
        if ($auth->role_id == USER_ROLE_ADMIN) {
            return true;
        }
    }

    public function updateUser($auth, $user)
    {
        if ($auth->id == $user->id || $auth->role_id == USER_ROLE_TEACHER) {
            return true;
        }
        return false;
    }
}
