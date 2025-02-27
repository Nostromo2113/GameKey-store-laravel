<?php

namespace App\Http\Requests\Admin\Order;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'nullable|integer|exists:users,id',
            'type' => 'nullable|string',
            'query' => 'nullable|integer'
        ];
    }
}
