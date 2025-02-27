<?php

namespace App\Services\Admin\PaymentGateway;

use App\Interfaces\PaymentGatewayInterface;

class FakePaymentGateway implements PaymentGatewayInterface
{

    public function pay(array $data): bool
    {
        return true;
    }

    public function getStatus(string $transactionId): string
    {
        return 'success';
    }
}
