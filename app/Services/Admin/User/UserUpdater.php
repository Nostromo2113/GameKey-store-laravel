<?php

namespace App\Services\Admin\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserUpdater
{
    /**
     * Обновляет данные пользователя и, при необходимости, его аватар.
     *
     * @param array $data Данные для обновления пользователя.
     * @param User $user Пользователь, которого нужно обновить.
     * @return User Обновленный пользователь.
     * @throws Exception Если произошла ошибка при обновлении.
     */
    public function updateUser(array $data, User $user): User
    {
        DB::beginTransaction();
        try {
            $this->fillUser($user, $data);

            DB::commit();

            return $user;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Заполняет данные пользователя.
     *
     * @param User $user Пользователь, которого нужно обновить.
     * @param array $data Данные для обновления.
     * @return void
     */
    private function fillUser(User $user, array $data): void
    {
        try {
            $imagePath = $this->updateAvatar($user, $data);

            $user->fill([
                'name'         => $data['name'],
                'email'        => $data['email'],
                'phone_number' => $data['phone_number'],
                'surname'      => $data['surname'],
                'patronymic'   => $data['patronymic'],
                'age'          => $data['age'],
                'address'      => $data['address'],
                'avatar'       => $imagePath,
            ])->save();
        } catch (\Exception $e) {
            throw new \Exception('Ошибка при записи пользователя: ' . $e->getMessage());
        }
    }




    /**
     * Обновляет аватар пользователя.
     *
     * @param User $user Пользователь, у которого обновляется аватар.
     * @param array $data Данные для обновления, включая файл аватара (если есть).
     * @return string Путь к новому или старому аватару.
     */
    private function updateAvatar(User $user, array $data): string
    {
        $oldImagePath = $user->avatar ?: 'uploads/users/avatars/default_avatar.jpg';

        try {
            // Если аватар был отправлен
            if (isset($data['file'])) {
                // Сохраняем новый аватар
                $imagePath = Storage::disk('public')->put('uploads/users/avatars', $data['file']);

                // Удаляем старый аватар, если он не является аватаром по умолчанию
                if ($oldImagePath != 'uploads/users/avatars/default_avatar.jpg' && Storage::disk('public')->exists($oldImagePath)) {
                    Storage::disk('public')->delete($oldImagePath);
                }
            } else {
                // Если новый аватар не отправлен, оставляем старый
                $imagePath = $oldImagePath;
            }

            return $imagePath;

        } catch (\Exception $e) {
            throw new \Exception('Ошибка при обновлении аватара пользователя: ' . $e->getMessage());
        }
    }
}
