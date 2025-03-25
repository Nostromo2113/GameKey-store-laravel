<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\Models\User;
use App\Services\Admin\User\UserService;

class UpdateController extends Controller
{
    private UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function __invoke(UpdateRequest $request, User $user)
    {
        $data = $request->validated();

            $user = $this->userService->update($data['user'], $user);

            return response()->json([
                'message' => 'Пользователь успешно обновлен',
                'data' => $user
            ], 200);
    }
}
