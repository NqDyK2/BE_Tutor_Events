<?php 

namespace App\Services;

use App\Models\User;

class AuthServices
{
    public function loginGoogle($googleUser)
    {
        $user = User::where('google_id', $googleUser->id)->first();

        if (!$user) {

            $user = User::create([
                'user_code' => explode("@", $googleUser->email)[0],
                'google_id' => $googleUser->id,
                'name' => $googleUser->name,
                'email' => $googleUser->email,
            ]);
        }
        elseif ($user->status == USER_STATUS_DEACTIVATE) {
            return response([
                'status' => false,
                'message' => 'This account has been blocked',
            ], 403);

        }
        
        return $user->createToken('API TOKEN')->plainTextToken;
    }
}