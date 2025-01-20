<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\UserResource;
use App\Models\User;

class ShowController extends Controller
{
    public function __invoke(User $user)
    {
        return new UserResource($user);
    }
}
