<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'nullable|string',
            'is_published' => 'nullable|boolean',
            'category_id' => 'nullable|integer|exists:categories,id'
        ];
    }
}
