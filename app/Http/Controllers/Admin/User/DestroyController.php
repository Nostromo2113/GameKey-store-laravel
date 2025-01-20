<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class DestroyController extends Controller
{
    public function __invoke(User $user)
    {
        $avatar = $user->avatar;
        if($avatar != 'uploads/users/avatars/default_avatar.jpg' && Storage::disk('public')->exists($avatar) && isset($data['file'])) {
            Storage::disk('public')->delete($avatar);
        }
        $user->delete();
        return response()->json('User removed', 200);
    }
}
