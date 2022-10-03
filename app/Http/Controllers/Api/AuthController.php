<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthServices;
use GuzzleHttp\Psr7\Request;
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

    public function getUrl()
    {
        return Socialite::driver('google')->redirect();
    }

    public function checkpoint()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $token = $this->authServices->loginGoogle($googleUser);
            return redirect("http://localhost:3000/checkpoint?token=" . $token);
        }
        catch (\Exception $error) {
            return response([
                'status' => 'false',
                'message' => 'Login Error',
            ], 500);
        }
    }
}
