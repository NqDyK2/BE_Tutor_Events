<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function before($auth, $user)
    {
        if ($auth->role_id == User::ROLE_ADMIN) {
            return true;
        }
    }

    public function updateUser($auth, $user)
    {
        if ($auth->id == $user->id) {
            return true;
        }
        return false;
    }
}
