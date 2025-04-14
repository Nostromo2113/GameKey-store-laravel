<?php

namespace App\Http\Requests\Admin\Comment;

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
            'id'    => 'nullable|integer',
            'query' => 'string|nullable',
            'type'  => 'nullable|string'
        ];
    }
}
