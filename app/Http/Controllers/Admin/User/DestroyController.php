<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Admin\User\UserDestroyer;

class DestroyController extends Controller
{
    private $userDestoyer;

    public function __construct(UserDestroyer $userDestoyer)
    {
        $this->userDestoyer = $userDestoyer;
    }
    public function __invoke(User $user)
    {
        $this->userDestoyer->destroyUser($user);

        return response()->json('User removed', 200);
    }
}
