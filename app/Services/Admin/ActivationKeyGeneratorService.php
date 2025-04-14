<?php

namespace App\Services\Admin;

use App\Models\ActivationKey;
use Illuminate\Support\Str;

class ActivationKeyGeneratorService
{
    public function generateForProduct(int $productId, int $quantity): void
    {
        $keys = [];
        for ($i = 0; $i < $quantity; $i++) {
            $keys[] = [
                'product_id' => $productId,
                'key' => $this->generate(),
                'created_at' => now(),
            ];
        }
        ActivationKey::insert($keys);
    }

    private function generate(): string
    {
        do {
            $key = strtoupper(
                Str::random(5) . '-' .
                Str::random(5) . '-' .
                Str::random(5)
            );
        } while (ActivationKey::where('key', $key)->exists());

        return $key;
    }
}
