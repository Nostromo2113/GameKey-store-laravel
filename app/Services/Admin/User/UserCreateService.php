<?php

namespace App\Services\Admin\User;

use App\Mail\UserRegistered;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserCreateService
{

    /**
     * Создает нового пользователя в базе данных.
     *
     * @param array $data Данные для создания пользователя.
     * @return User $newUser Созданный пользователь.
     */
    public function createUser(array $data): User
    {
        $avatar = 'uploads/users/avatars/default_avatar.jpg';

        $password = Hash::make(isset($data['password']) ? $data['password'] : $this->genPassword(6));

        $newUser = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_number' => $data['phone'],
            'password' => $password,
            'surname' => $data['surname'],
            'patronymic' => $data['patronymic'],
            'age' => $data['age'],
            'address' => $data['address'],
            'avatar' => $avatar,
        ]);

//          if(!isset($data['password'])) {
//              $this->sendEmail($newUser, $password);
//          }
        return $newUser;
    }


    /**
     * Генерирует случайный пароль заданной длины.
     *
     * @param int $length Длина пароля (по умолчанию 6 символов).
     * @return string Сгенерированный пароль.
     */
    private function genPassword(int $length = 6): string
    {
        $chars = 'qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP';
        $size = strlen($chars) - 1;
        $password = '';

        while ($length--) {
            $password .= $chars[random_int(0, $size)];
        }
        return $password;
    }


    /**
     * Отправляет email с паролем и email для верификации.
     *
     * @param User $newUser Новый пользователь.
     * @param string $password Пароль пользователя.
     * @return void
     */
    private function sendEmail(User $newUser, string $password): void
    {
        Mail::to($newUser->email)->send(new UserRegistered($newUser, $password));

        $newUser->sendEmailVerificationNotification();
    }


}
