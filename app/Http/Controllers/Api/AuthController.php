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
            "message" => "Working..."
        ], 200);

        $tokens = DevTokens::all();
        return view('auth.login', [
            'tokens' => $tokens
        ]);
    }

    public function getAuthDetail()
    {
        $user = $this->authServices->getAuthDetail();

        return response([
            'data' => $user
        ], 200);
    }

    public function storeToken(Request $request)
    {
        DevTokens::create([
            'token' => $request->token,
            'desc' => $request->desc
        ]);
    }

    public function getUrl()
    {
        return Socialite::driver('google')->redirect();
    }

    public function checkpoint()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            return redirect(env('FRONTEND_URL') . "/checkpoint?token=" . $token);

            $token = $this->authServices->loginGoogle($googleUser);
            return view('auth.redirect', [
                'token' => $token
            ]);
        } catch (\Exception $error) {
            return response([
                'message' => 'Đăng nhập thất bại là mẹ thành công',
            ], 401);
        }
    }
}
