<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Services\UserServices;

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
            'status' => true,
            'data' => $users
        ], 200);
    }

    public function show($id)
    {
        $users = $this->userServices->getOne($id);
        
        return response([
            'status' => true,
            'data' => $users
        ], 200);
    }

    public function update(UpdateUserRequest $request)
    {
        $user = $request->get('user');
        $this->authorize('updateUser', $user);

        $updated = $this->userServices->update($user, $request->all());

        return response([
            'status' => $updated,
            'message' => $updated ? 'Update user successfully' : 'Update failled'
        ], $updated ? 200 : 409);

    }
}
