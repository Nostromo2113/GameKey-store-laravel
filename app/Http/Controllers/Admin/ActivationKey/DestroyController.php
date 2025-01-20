<?php

namespace App\Http\Controllers\Admin\ActivationKey;

use App\Http\Controllers\Controller;
use App\Models\ActivationKey;

class DestroyController extends Controller
{
    public function __invoke($id)
    {
        $activationKey = ActivationKey::findOrFail($id);
        $activationKey->delete();
        return response()->json('Activation key removed', 200);
    }
}
