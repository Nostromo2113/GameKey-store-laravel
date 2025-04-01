<?php

namespace App\Http\Requests\Admin\ActivationKey;

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
            'product_id' => 'integer|exists:products,id',
        ];
    }
}
