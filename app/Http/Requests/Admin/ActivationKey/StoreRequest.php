<?php

namespace App\Http\Requests\Admin\ActivationKey;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'activation_key' => 'required|array',
            'activation_key.product_id' => 'required|integer|exists:products,id',
            'activation_key.key' => [
                'nullable',
                'string',
                'regex:/^[A-Z0-9]{5}-[A-Z0-9]{5}-[A-Z0-9]{5}$/',
                Rule::unique('activation_keys', 'key')->whereNull('deleted_at')
            ],
        ];
    }
}
