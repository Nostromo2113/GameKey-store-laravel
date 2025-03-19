<?php

namespace App\Http\Requests\Admin\Cart\CartProduct;

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
            'product' => 'required|array',
            'product.product_id' => 'required|integer',
            'product.quantity' => 'integer',
            'product.price' => 'integer',
        ];
    }
}
