<?php

namespace App\Http\Requests\Admin\User\UserOrder;

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
            'order' => 'required|array',
            'order.user_id' => 'required|integer|exists:users,id',
            'order.order_products' => 'array|nullable',
            'order.order_products.*.id' => 'integer|exists:products,id',
            'order.order_products.*.quantity' => 'nullable|integer|min:1',
        ];
    }
}
