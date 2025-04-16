<?php

namespace App\Http\Controllers\Admin\User\UserOrder;

use App\Http\Controllers\Controller;
use App\Models\User;

class IndexController extends Controller
{
    public function __invoke(User $user)
    {
        $orders = $user->orders()->paginate(8);

        return $orders;
    }
}
