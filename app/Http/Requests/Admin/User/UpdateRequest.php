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
            'id' => 'required|integer|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->id,
            'phone_number' => 'required|string',
            'surname' => 'required|string|max:255',
            'patronymic' => 'nullable|string|max:255',
            'age' => 'required|integer|min:0',
            'address' => 'nullable|string|max:255',
            'file' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
    }
}
