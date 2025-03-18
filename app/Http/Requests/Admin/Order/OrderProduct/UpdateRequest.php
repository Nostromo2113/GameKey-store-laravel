<?php

namespace App\Http\Requests\Admin\Order\OrderProduct;

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
            'order_products' => 'array|required',
            'order_products.*.id' => 'required|integer|exists:products,id',
            'order_products.*.quantity' => 'required|integer',
        ];
    }
}
