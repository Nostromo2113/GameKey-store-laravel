<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\RegistrationRequest;
use App\Services\Admin\User\UserService;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegistrationController extends Controller
{
    private UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function __invoke(RegistrationRequest $request)
    {
        $data = $request->validated();
        try {
            $newUser = $this->userService->store($data);

            $token = JWTAuth::fromUser($newUser);

            return response()->json([
                'message'      => 'Пользователь создан',
                'data'         => $newUser,
                'access_token' => $token
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ошибка при создании пользователя: ' . $e->getMessage()
            ], 500);
        }
    }
}
