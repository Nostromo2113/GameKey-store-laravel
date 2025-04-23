<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\PasswordResetRequest;
use App\Services\Admin\Auth\AuthService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PasswordResetController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function __invoke(PasswordResetRequest $request)
    {
            $this->authService->resetPassword($request->validated());

            return response()->json([
                'message' => 'Письмо с новым паролем успешно отправлено'
            ]);
    }
}
