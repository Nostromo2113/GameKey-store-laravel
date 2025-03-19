<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'product' => 'required|array',

            'product.title' => 'required|string|max:255',
            'product.description' => 'required|string',
            'product.publisher' => 'required|string|max:255',
            'product.release_date' => 'required|date',
            'product.file' => 'nullable|image',
            'product.price' => 'required|numeric|min:0',
            'product.is_published' => 'required|boolean',
            'product.category' => 'required|exists:categories,id',
            'product.genres' => 'array|nullable',
            'product.genres.*' => 'exists:genres,id',

            'product.technical_requirements' => 'array|nullable',
            'product.technical_requirements.platform' => 'required|string',
            'product.technical_requirements.os' => 'required|string',
            'product.technical_requirements.cpu' => 'required|string',
            'product.technical_requirements.gpu' => 'required|string',
            'product.technical_requirements.ram' => 'required|integer',
            'product.technical_requirements.storage' => 'required|integer',
        ];
    }
}
