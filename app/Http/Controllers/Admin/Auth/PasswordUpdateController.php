<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\PasswordRequest;
use Illuminate\Support\Facades\Hash;

class PasswordUpdateController extends Controller
{
    public function changePassword(PasswordRequest $request)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if (!Hash::check($request['current_password'], $user->password)) {
            return response()->json(['error' => 'Старый пароль введен неверно'], 422);
        }

        $user->password = Hash::make($request['new_password']);
        $user->save();

        return response()->json([
            'message' => 'Пароль изменен'
        ], 200);

    }

}
