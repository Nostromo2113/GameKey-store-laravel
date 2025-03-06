<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreRequest;
use App\Services\Admin\User\UserService;
use Tymon\JWTAuth\Facades\JWTAuth;


class StoreController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();

        $newUser = $this->userService->store($data);

        $token = JWTAuth::fromUser($newUser);

        return response()->json([
            'message' => 'User created successfully',
            'data' => $newUser,
            'access_token' => $token
        ], 201);
    }
}
