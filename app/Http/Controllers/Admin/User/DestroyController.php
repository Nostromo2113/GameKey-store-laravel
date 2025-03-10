<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Admin\User\UserService;
use Illuminate\Support\Facades\Storage;

class DestroyController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function __invoke(User $user)
    {
        $this->userService->destroy($user);
        return response()->json('User removed', 200);
    }
}
