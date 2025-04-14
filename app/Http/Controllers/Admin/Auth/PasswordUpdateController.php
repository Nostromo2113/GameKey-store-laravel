<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\PasswordRequest;
use App\Services\Admin\Auth\AuthService;
use Illuminate\Validation\ValidationException;

class PasswordUpdateController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function __invoke(PasswordRequest $request)
    {
        $data = $request->validated();
        $user = auth('api')->user();
        if ($user) {
            try {
                $this->authService->changePassword($user, $data);

                return response()->json([
                    'message' => 'Пароль успешно изменен'
                ], 200);

            } catch (ValidationException $e) {

                return response()->json([
                    'error'  => $e->getMessage(),
                    'errors' => $e->errors()
                ], 422);

            }

        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

}
