<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

interface ActivationKeyRepositoryInterface
{
    public function selectKeys(array $requestOrderProducts, Collection $products, Collection $existingProducts): ?Collection;
    public function bindKeys(array $data): void;
}
