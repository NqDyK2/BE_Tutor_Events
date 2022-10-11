<?php 

namespace App\Services;

use App\Models\User;

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
        }
        elseif ($user->status == USER_STATUS_DEACTIVATE) {
            return response([
                'message' => 'This account has been blocked',
            ], 403);
        }
        
        return $user->createToken('API TOKEN')->plainTextToken;
    }
}