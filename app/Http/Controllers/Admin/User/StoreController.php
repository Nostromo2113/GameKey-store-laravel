<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreRequest;
use App\Services\Admin\Cart\CartStoreService;
use App\Services\Admin\UserStoreService;
use Tymon\JWTAuth\Facades\JWTAuth;


class StoreController extends Controller
{
    public function __construct(UserStoreService $userService, CartStoreService $cartService)
    {
        $this->userService = $userService;
        $this->cartService = $cartService;
    }

    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        try {
          $newUser = $this->userService->store($data);
            $token = JWTAuth::fromUser($newUser);

            return response()->json([
                'message' => 'User created successfully',
                'data' => $newUser,
                'access_token' => $token
            ], 201);

        } catch(\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

}
