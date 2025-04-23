<?php

namespace App\Services\Admin\Auth;

use App\Mail\PasswordReset;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Jobs\SendMailJob;

class AuthService
{
    public function changePassword(User $user, array $data): bool
    {
        if (!Hash::check($data['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Текущий пароль неверен']
            ]);
        }

        return $user->update([
            'password' => Hash::make($data['new_password'])
        ]);
    }

    public function resetPassword(array $data): bool
    {
        $email = $data['email'];

        $user = User::where('email', $email)->firstOrFail();

        $newPassword = Str::random(8);
        $user->update(['password' => Hash::make($newPassword)]);

        SendMailJob::dispatch(
            PasswordReset::class,
            [
                'password' => $newPassword,
                'user'     => $user
            ],
            $email
        );

        return true;
    }
}
