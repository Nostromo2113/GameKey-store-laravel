<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\User\UserResource;
use App\Models\User;

class IndexController extends Controller
{
    public function __invoke()
    {
       $users = User::all();
       return UserResource::collection($users);
    }
}
