<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\Http\Resources\Admin\User\UserResource;
use App\Models\User;
use App\Services\Admin\User\UserUpdater;

class UpdateController extends Controller
{
    private UserUpdater $userUpdater;
    public function __construct(UserUpdater $userUpdater)
    {
        $this->userUpdater = $userUpdater;
    }

    public function __invoke(UpdateRequest $request, User $user)
    {
        $data = $request->validated();
        $user = $this->userUpdater->updateUser($data['user'], $user);

        return response()->json([
            'message' => 'Пользователь успешно обновлен',
            'data'    => new UserResource($user)
        ], 200);
    }
}
