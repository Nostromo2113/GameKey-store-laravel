<?php

namespace App\Services\Admin\User;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserDestroyer
{
    public function destroyUser(User $user): void
    {
            $this->deleteUserAvatar($user);
            $user->delete();
    }

    private function deleteUserAvatar(User $user): void
    {
            $avatar = $user->avatar;
            if ($avatar != 'uploads/users/avatars/default_avatar.jpg' && Storage::disk('public')->exists($avatar) && isset($data['file'])) {
                Storage::disk('public')->delete($avatar);
            }
    }
}
