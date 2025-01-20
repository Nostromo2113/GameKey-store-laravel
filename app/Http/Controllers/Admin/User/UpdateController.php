<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request)
    {
        // Получаем валидированные данные
        $data = $request->validated();
        // Найти категорию по ID
        $user = User::findOrFail($data['id']);
        $oldImagePath = $user->avatar;
        //Удаляем старое изображение
        if($oldImagePath != 'uploads/users/avatars/default_avatar.jpg' && Storage::disk('public')->exists($oldImagePath) && isset($data['file'])) {
            Storage::disk('public')->delete($oldImagePath);
        }

        if(isset($data['file'])) {
            $data['file'] = Storage::disk('public')->put('uploads/users/avatars', $data['file']);
        } else {
            $data['file'] = $oldImagePath;
        }


        // Обновить категорию с новыми данными
        $user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
            'surname' => $data['surname'],
            'patronymic' => $data['patronymic'],
            'gender' => $data['gender'],
            'age' => $data['age'],
            'address' => $data['address'],
            'avatar' => $data['file']
        ])->save();

        return response()->json([
            'message' => 'Пользователь обновлена успешно',
        ], 200);
    }
}
