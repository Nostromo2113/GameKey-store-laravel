<?php

namespace App\Http\Controllers\Admin\ActivationKey;

use App\Http\Controllers\Controller;
use App\Models\ActivationKey;

class DestroyController extends Controller
{
    public function __invoke(ActivationKey $activationKey)
    {
        // policy
        $this->authorize('delete', $activationKey);

        $activationKey->delete();

        return response()->json('Activation key removed', 200);
    }
}
