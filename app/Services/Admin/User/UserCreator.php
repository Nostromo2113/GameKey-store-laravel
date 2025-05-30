<?php

namespace App\Services\Admin\User;

use App\Jobs\SendMailJob;
use App\Mail\UserRegistered;
use App\Models\User;
use App\Services\Admin\Cart\CartCreator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserCreator
{
    private $cartService;

    public function __construct(CartCreator $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Создает нового пользователя в базе данных.
     *
     * @param array $data Данные для создания пользователя.
     * @return User $newUser Созданный пользователь.
     */
    public function createUser(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $avatar   = 'uploads/users/avatars/default_avatar.jpg';
            $password = isset($data['password']) ? $data['password'] : $this->genPassword(6);

            $newUser = User::create([
                'name'         => $data['name'],
                'email'        => $data['email'],
                'phone_number' => $data['phone_number'],
                'password'     => Hash::make($password),
                'surname'      => $data['surname'],
                'patronymic'   => $data['patronymic'],
                'age'          => $data['age'],
                'address'      => $data['address'],
                'avatar'       => $avatar,
                'role'         => 'admin' //Для теста
            ]);

            $this->cartService->store($newUser);

            if (!isset($data['password'])) {
                $this->sendProfileInfoMail($newUser, $password);
            }

            $newUser->sendEmailVerificationNotification();

            return $newUser;
        });
    }


    /**
     * Генерирует случайный пароль заданной длины.
     *
     * @param int $length Длина пароля (по умолчанию 6 символов).
     * @return string Сгенерированный пароль.
     */
    private function genPassword(int $length = 6): string
    {
        $chars    = 'qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP';
        $size     = strlen($chars) - 1;
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
    private function sendProfileInfoMail(User $newUser, string $password): void
    {
        SendMailJob::dispatch(
            UserRegistered::class,
            [
                'password' => $password,
                'user'     => $newUser
            ],
            $newUser->email
        );
    }
}
