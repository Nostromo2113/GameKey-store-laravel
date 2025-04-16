<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user'              => 'required|array',
            'user.name'         => 'required|string|max:255|min:2',
            'user.email'        => 'required|string|email|max:255|unique:users,email',
            'user.surname'      => 'required|string|max:255',
            'user.patronymic'   => 'required|string|max:255',
            'user.age'          => 'required|integer|min:1|max:120',
            'user.address'      => 'required|string|max:255',
            'user.phone_number' => 'required|string|min:11|max:15'
        ];
    }
}
