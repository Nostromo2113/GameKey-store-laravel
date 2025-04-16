<?php

namespace App\Services\Admin\User;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserDestroyService
{
    public function destroyUser(User $user): void
    {
        try {
            $this->deleteUserAvatar($user);
            $user->delete();
        } catch (\Exception $e) {
            throw new \Exception('Ошибка при удалении пользователя: ' . $e->getMessage());
        }
    }

    private function deleteUserAvatar(User $user): void
    {
        try {
            $avatar = $user->avatar;
            if ($avatar != 'uploads/users/avatars/default_avatar.jpg' && Storage::disk('public')->exists($avatar) && isset($data['file'])) {
                Storage::disk('public')->delete($avatar);
            }
        } catch (\Exception $e) {
            throw new \Exception('Ошибка при удалении аватара пользователя: ' . $e->getMessage());
        }
    }
}
