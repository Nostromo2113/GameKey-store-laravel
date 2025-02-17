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
            'id' => 'required|integer|exists:products,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'publisher' => 'required|string|max:255',
            'release_date' => 'required|date',
            'file' => 'nullable|image',
            'price' => 'required|numeric|min:0',
            'amount' => 'required|integer|min:0',
            'is_published' => 'required|boolean',
            'category' => 'required|exists:categories,id',
            'genres' => 'array|nullable',
            'genres.*' => 'exists:genres,id',
            'technical_requirements' => 'array|nullable',
            'technical_requirements.product_id' => 'required|exists:products,id',
            'technical_requirements.id' => 'required|exists:technical_requirements,id',
            'technical_requirements.platform' => 'required|string',
            'technical_requirements.os' => 'required|string',
            'technical_requirements.cpu' => 'required|string',
            'technical_requirements.gpu' => 'required|string',
            'technical_requirements.ram' => 'required|integer',
            'technical_requirements.storage' => 'required|integer',


        ];
    }
}
