<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

interface ActivationKeyRepositoryInterface
{
    public function selectKeys(array $requestOrderProducts, Collection $requestProducts, Collection $existingProducts): ?Collection;
    public function bindKeys(array $data): void;
}
