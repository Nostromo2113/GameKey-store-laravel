<?php

namespace App\Services\Admin;

use App\Mail\UserRegistered;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Exception;

class UserStoreService
{
    /**
     * Создает нового пользователя и возвращает ответ с данными пользователя и токеном.
     *
     * @param array $data Данные для создания пользователя.
     * @return User
     * @throws Exception
     */
    public function store(array $data): User
    {
        DB::beginTransaction();

        try {
            $user = User::where('email', $data['email'])->first();

            if ($user) {
                throw new Exception('User already exists');
            }

            $password = isset($data['password']) ? Hash::make($data['password']) : $this->genPassword(6);

            $newUser = $this->createUser($data, $password);

            $this->sendEmail($newUser, $password);

            DB::commit();

            return $newUser;
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }
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

        // Генерация пароля
        while ($length--) {
            $password .= $chars[random_int(0, $size)];
        }

        return $password;
    }

    /**
     * Создает нового пользователя в базе данных.
     *
     * @param array $data Данные для создания пользователя.
     * @param string $password Пароль пользователя.
     * @return User Созданный пользователь.
     */
    private function createUser(array $data, string $password): User
    {
        $avatar = 'uploads/users/avatars/default_avatar.jpg';

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($password),
            'surname' => $data['surname'],
            'patronymic' => $data['patronymic'],
            'gender' => $data['gender'],
            'age' => $data['age'],
            'address' => $data['address'],
            'avatar' => $avatar,
        ]);
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
