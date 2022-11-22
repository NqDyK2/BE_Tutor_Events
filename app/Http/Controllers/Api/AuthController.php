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
        return response([
            "message" => "Working..."
        ], 200);
    }

    public function getAuthDetail()
    {
        $user = $this->authServices->getAuthDetail();

        return response([
            'data' => $user
        ], 200);
    }

    public function getUrl()
    {
        $url =  Socialite::driver('google')->redirect()->getTargetUrl();
        return response([
            "url" => $url
        ], 200);
    }

    public function checkpoint()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $token = $this->authServices->loginGoogle($googleUser);

            return response([
                'token' => $token
            ], 200);

        } catch (\Exception $error) {
            return response([
                'message' => 'Đăng nhập thất bại, thử lại sau ít phút',
            ], 401);
        }
    }
}
