<?php

namespace App\Services\Admin\User\UserOrder;

use App\Models\User;
use App\Services\Admin\User\UserCreateService;
use App\Services\Admin\User\UserDestroyService;
use App\Services\Admin\User\UserUpdateService;
use Illuminate\Database\Eloquent\Collection;

class UserOrderService
{
    public function getOrdersByUserId(int $userId): Collection
    {
        $user = User::findOrFail($userId);
        $orders = $user->orders;
        return $orders;
    }
}
