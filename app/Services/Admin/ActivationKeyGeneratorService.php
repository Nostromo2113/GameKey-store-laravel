<?php

namespace App\Services\Admin;

use App\Models\ActivationKey;
use Illuminate\Support\Str;

class ActivationKeyGeneratorService
{
    public function generate(): string
    {
        do {
            $key = strtoupper(Str::random(5)) . '-' . strtoupper(Str::random(5)) . '-' . strtoupper(Str::random(5));
        } while (ActivationKey::where('key', $key)->exists());

        return $key;
    }
}
