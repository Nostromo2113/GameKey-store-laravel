<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\PasswordResetRequest;
use App\Mail\PasswordReset;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class PasswordResetController extends Controller
{
    public function sendResetPasswordMail(PasswordResetRequest $request)
    {
        $data = $request->validated();
        dd($data);
        $email = $data['email'];
        $newPassword = Str::random(8);
        $user = User::where('email', $email)->first();
        if(!$user) {
            $errorResponse = "Пользователь с email: {$email} не найден.";
            return response()->json([$errorResponse], 422);

        }
        $user->update(['password' => Hash::make($newPassword)]);
        Mail::to($email)->send(new PasswordReset($user, $newPassword));
        return response()->json(['message' => 'Письмо с новым паролем успешно отправлено.']);
    }
}
