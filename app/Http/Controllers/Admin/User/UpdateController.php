<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\Models\User;
use App\Services\Admin\UserUpdateService;

class UpdateController extends Controller
{
    public function __construct(UserUpdateService $userService)
    {
        $this->userService = $userService;
    }

    public function __invoke(UpdateRequest $request, User $user)
    {
        $data = $request->validated();
        try {
            $user = $this->userService->update($data, $user);

            return response()->json([
                'message' => 'Пользователь обновлен успешно',
                'data' => $user
            ], 200);

        } catch(\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
