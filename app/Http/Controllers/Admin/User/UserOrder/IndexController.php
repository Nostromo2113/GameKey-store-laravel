<?php

namespace App\Http\Controllers\Admin\User\UserOrder;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Admin\User\UserOrder\UserOrderService;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    private $userOrderService;

    public function __construct(UserOrderService $userOrderService)
    {
        $this->userOrderService = $userOrderService;
    }

    public function __invoke(User $user)
    {
        $orders = $this->userOrderService->getOrdersByUserId($user->id);
        return $orders;
    }
}
