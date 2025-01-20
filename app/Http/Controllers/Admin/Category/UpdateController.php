<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\UpdateRequest;
use App\Models\Category;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request)
    {
        // Получаем валидированные данные
        $data = $request->validated();
        // Найти категорию по ID
        $category = Category::findOrFail($data['id']);

        // Обновить категорию с новыми данными
        $category->update([
            'title' => $data['title']
        ]);

        return response()->json([
            'message' => 'Категория обновлена успешно',
            'data' => $category
        ], 200);
    }
}
