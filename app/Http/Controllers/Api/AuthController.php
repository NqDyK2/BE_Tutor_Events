<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DevTokens;
use App\Services\AuthServices;
use Illuminate\Http\Request;
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
            "message" => "Mò vào tận đây thì bạn cũng đỉnh đấy!"
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
        return Socialite::driver('google')->redirect()->getTargetUrl();
    }

    public function checkpoint()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $token = $this->authServices->loginGoogle($googleUser);
            return response([
                'token' => $token
            ]);
        }
        catch (\Exception $error) {
            return response([
                'message' => 'Login Error',
            ], 500);
        }
    }
}
