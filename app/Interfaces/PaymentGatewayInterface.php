<?php

namespace App\Interfaces;

interface PaymentGatewayInterface
{
    public function pay(array $data): bool;
    public function getStatus(string $transactionId): string;
}
