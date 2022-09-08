<?php

namespace App\Services;

use App\Models\User;

class UserServices
{
    public function getAll()
    {
        $user = User::select('id', 'user_code', 'name', 'email', 'avatar', 'gender', 'address', 'phone_number', 'dob', 'role_id', 'major_id')
        ->get();
        return $user;
    }

    public function getOne($id)
    {
        return User::find($id);
    }

    public function update($user, $data)
    {
        return $user->update($data);
    }
}