<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreRequest;
use App\Http\Resources\Admin\User\UserResource;
use App\Services\Admin\User\UserCreator;
use Tymon\JWTAuth\Facades\JWTAuth;

class StoreController extends Controller
{
    private UserCreator $userCreator;

    public function __construct(UserCreator $userCreator)
    {
        $this->userCreator = $userCreator;
    }

    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();

        $newUser = $this->userCreator->createUser($data['user']);

        $token = JWTAuth::fromUser($newUser);

        return response()->json([
            'message'      => 'Пользователь создан',
            'data'         => new UserResource($newUser),
            'access_token' => $token
        ], 201);
    }
}
