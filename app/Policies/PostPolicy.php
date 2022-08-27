<?php

namespace App\Policies;

use App\Models\Classroom;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
    }
    // public function delete(Auth $auth, Classroom $id) 
    // {
    //     dd('Stop here');
        
    //     return true;

    // }
}
