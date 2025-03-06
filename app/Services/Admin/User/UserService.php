<?php

namespace App\Services\Admin\User;

use App\Models\User;

class UserService
{
    private $userCreateService;
    private $userUpdateService;
    private $userDestroyService;

    public function __construct(
        UserCreateService  $userCreateService,
        UserUpdateService  $userUpdateService,
        UserDestroyService $userDestroyService
    )

    {
        $this->userCreateService = $userCreateService;
        $this->userUpdateService = $userUpdateService;
        $this->userDestroyService = $userDestroyService;
    }

    public function store(array $data): User
    {
       $newUser = $this->userCreateService->createUser($data);
       return $newUser;
    }

    public function update(array $data, User $user): User
    {
        $updatedUser = $this->userUpdateService->updateUser($data, $user);
        return $updatedUser;
    }

    public function destroy(User $user): void
    {
        $this->userDestroyService->destroyUser($user);
    }
}
