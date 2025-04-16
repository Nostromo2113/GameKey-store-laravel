<?php

namespace App\Http\Resources\Admin\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'email'        => $this->email,
            'phone_number' => $this->phone_number,
            'name'         => $this->name,
            'surname'      => $this->surname,
            'patronymic'   => $this->patronymic,
            'age'          => $this->age,
            'address'      => $this->address,
            'avatar'       => $this->avatar,
            'role'         => $this->role
        ];
    }
}
