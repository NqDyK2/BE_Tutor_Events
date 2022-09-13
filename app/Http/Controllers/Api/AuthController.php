<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuthServices;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    private $authServices;

    public function __construct(AuthServices $authServices)
    {
        $this->authServices = $authServices;
    }

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

            return $this->authServices->loginGoogle($googleUser);
        }
        catch (\Exception $error) {
            return response([
                'status' => 'false',
                'message' => 'Login Error',
            ], 500);
        }
    }
}
