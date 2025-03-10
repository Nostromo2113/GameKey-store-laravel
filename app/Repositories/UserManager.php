<?php

namespace App\Repositories;

use App\Mail\UserRegistered;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class UserManager
{
//    /**
//     * Создает нового пользователя в базе данных.
//     *
//     * @param array $data Данные для создания пользователя.
//     * @return User Созданный пользователь.
//     */
//    public function createUser(array $data): User
//    {
//        $avatar = 'uploads/users/avatars/default_avatar.jpg';
//
//        $password = isset($data['password']) ? Hash::make($data['password']) : $this->genPassword(6);
//
//           $newUser = User::create([
//            'name' => $data['name'],
//            'email' => $data['email'],
//            'phone_number' => $data['phone'],
//            'password' => Hash::make($password),
//            'surname' => $data['surname'],
//            'patronymic' => $data['patronymic'],
//            'age' => $data['age'],
//            'address' => $data['address'],
//            'avatar' => $avatar,
//        ]);
//
////          if(!isset($data['password'])) {
////              $this->sendEmail($newUser, $password);
////          }
//           return $newUser;
//    }
//
//    /**
//     * Отправляет email с паролем и email для верификации.
//     *
//     * @param User $newUser Новый пользователь.
//     * @param string $password Пароль пользователя.
//     * @return void
//     */
//    private function sendEmail(User $newUser, string $password): void
//    {
//        Mail::to($newUser->email)->send(new UserRegistered($newUser, $password));
//
//        $newUser->sendEmailVerificationNotification();
//    }
//
//
//    /**
//     * Генерирует случайный пароль заданной длины.
//     *
//     * @param int $length Длина пароля (по умолчанию 6 символов).
//     * @return string Сгенерированный пароль.
//     */
//    private function genPassword(int $length = 6): string
//    {
//        $chars = 'qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP';
//        $size = strlen($chars) - 1;
//        $password = '';
//
//        // Генерация пароля
//        while ($length--) {
//            $password .= $chars[random_int(0, $size)];
//        }
//
//        return $password;
//    }
//
//    /**
//     * Заполняет данные пользователя.
//     *
//     * @param User $user Пользователь, которого нужно обновить.
//     * @param array $data Данные для обновления.
//     * @return void
//     */
//    public function fillUser(User $user, array $data): void
//    {
//        $imagePath = $this->updateAvatar($user, $data);
//
//        $user->fill([
//            'name' => $data['name'],
//            'email' => $data['email'],
//            'phone_number' => $data['phone_number'],
//            'surname' => $data['surname'],
//            'patronymic' => $data['patronymic'],
//            'age' => $data['age'],
//            'address' => $data['address'],
//            'avatar' => $imagePath,
//        ])->save();
//    }
//
//    /**
//     * Обновляет аватар пользователя.
//     *
//     * @param User $user Пользователь, у которого обновляется аватар.
//     * @param array $data Данные для обновления, включая файл аватара (если есть).
//     * @return string Путь к новому или старому аватару.
//     */
//    private function updateAvatar(User $user, array $data): string
//    {
//        $oldImagePath = $user->avatar;
//
//        try {
//            // Если аватар был отправлен
//            if (isset($data['file'])) {
//                // Сохраняем новый аватар
//                $imagePath = Storage::disk('public')->put('uploads/users/avatars', $data['file']);
//
//                // Удаляем старый аватар, если он не является аватаром по умолчанию
//                if ($oldImagePath != 'uploads/users/avatars/default_avatar.jpg' && Storage::disk('public')->exists($oldImagePath)) {
//                    Storage::disk('public')->delete($oldImagePath);
//                }
//            } else {
//                // Если новый аватар не отправлен, оставляем старый
//                $imagePath = $oldImagePath;
//            }
//
//            return $imagePath;
//
//        } catch (\Exception $e) {
//            throw $e;
//        }
//    }

}
