<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\PasswordRequest;
use App\Services\Admin\Auth\AuthService;
use Illuminate\Validation\ValidationException;

class PasswordUpdateController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function __invoke(PasswordRequest $request)
    {
        $data = $request->validated();
        $user = auth('api')->user();
        if ($user) {
                $this->authService->changePassword($user, $data);

                return response()->json([
                    'message' => 'Пароль успешно изменен'
                ], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

}
