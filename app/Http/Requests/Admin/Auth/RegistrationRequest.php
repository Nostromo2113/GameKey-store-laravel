<?php

namespace App\Http\Requests\Admin\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
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
            'name'  => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'max:255',
                'unique:users,email',
                'lowercase'
            ],
            'surname'    => 'required|string|max:255',
            'patronymic' => 'required|string|max:255',
            'age'        => 'required|integer|max:120',
            'address'    => 'required|string|max:500',
            'password'   => [
                'required',
                'string',
                'min:6',
                'confirmed',
            ],
            'phone_number' => [
                'required',
                'string',
                'unique:users,phone_number'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'email.lowercase' => 'Email должен быть в нижнем регистре',
        ];
    }
}
