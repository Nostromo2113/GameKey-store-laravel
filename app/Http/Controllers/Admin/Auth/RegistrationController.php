<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\RegistrationRequest;
use App\Services\Admin\User\UserCreator;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegistrationController extends Controller
{
    private UserCreator $userCreator;
    public function __construct(UserCreator $userCreator)
    {
        $this->userCreator = $userCreator;
    }

    public function __invoke(RegistrationRequest $request)
    {
        $data = $request->validated();

            $newUser = $this->userCreator->createUser($data);

            $token = JWTAuth::fromUser($newUser);

            return response()->json([
                'message'      => 'Пользователь создан',
                'data'         => $newUser,
                'access_token' => $token
            ], 201);
    }
}
