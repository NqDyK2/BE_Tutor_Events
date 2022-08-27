<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function checkpoint()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            if ($googleUser->user['hd'] != 'fpt.edu.vn') {
                return response()->json([
                    'status' => false,
                    'message' => 'Email not accepted',
                ], 401);
            }
            
            $user = User::updateOrCreate([
                'google_id' => $googleUser->id,
            ], [
                'google_id' => $googleUser->id,
                'name' => $googleUser->name,
                'email' => $googleUser->email,
            ]);
            return response()->json([
                'status' => true,
                'message' => 'User created successfully',
                'token' => $user->createToken('API TOKEN')->plainTextToken
            ], 201);
        }
        catch (\Exception $error) {
            return response()->json([
                'message' => 'Login Error',
                'error' => $error,
            ], 500);
        }
    }
}
