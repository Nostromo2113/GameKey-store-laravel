<?php

namespace App\Http\Requests\Admin\ActivationKey;

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
            'id' => 'required|integer|exists:products,id',
            'key' => 'nullable|string|regex:/^[A-Z0-9]{5}-[A-Z0-9]{5}-[A-Z0-9]{5}$/|size:17|unique:activation_keys,key',

        ];
    }
}
