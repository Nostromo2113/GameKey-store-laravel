<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'user' => 'array',
            'user.name' => 'required|string|max:255',
            'user.email' => 'required|string|email|max:255|unique:users,email,' . $this->user['id'],
            'user.phone_number' => 'required|string',
            'user.surname' => 'required|string|max:255',
            'user.patronymic' => 'nullable|string|max:255',
            'user.age' => 'required|integer|min:0',
            'user.address' => 'nullable|string|max:255',
            'user.file' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
    }
}
