<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Services\UserServices;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isNull;

class UserController extends Controller
{
    private $userServices;

    public function __construct(UserServices $userServices)
    {
        $this->userServices = $userServices;
    }

    public function get()
    {
        $users = $this->userServices->getAll();

        return response([
            'data' => $users
        ], 200);
    }

    public function show($id)
    {
        $users = $this->userServices->getOne($id);

        return response([
            'data' => $users
        ], 200);
    }

    public function update(UpdateUserRequest $request)
    {
        $user = $request->get('user');
        $this->authorize('updateUser', $user);

        $updated = $this->userServices->update($user, $request->all());

        return response([
            'message' => $updated ? 'Cập nhật tài khoản thành công' : 'Cập nhật tài khoản thất bại'
        ], $updated ? 200 : 400);
    }

    public function getSetting()
    {
        $setting = Auth::user()->setting;

        if (!$setting) {
            UserSetting::create(['user_id' => Auth::id()]);
            $setting = UserSetting::where('user_id', Auth::id())->first();
        }

        return response([
            'data' => $setting
        ], 200);
    }

    public function updateSetting(Request $request)
    {
        $this->userServices->updateSetting($request->input());

        return response([
            'message' => 'Cập nhật thành công'
        ], 200);
    }
}
