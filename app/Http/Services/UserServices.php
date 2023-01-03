<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserServices
{
    public function getAll()
    {
        $user = User::select('id', 'code', 'name', 'email', 'avatar', 'gender', 'address', 'phone_number', 'dob', 'role_id')
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

    public function updateSetting($data)
    {
        $user = Auth::user();

        $result = $user->setting()->updateOrCreate([
            'user_id' => $user->id
        ], $data);
        
        return $result;
    }
}
