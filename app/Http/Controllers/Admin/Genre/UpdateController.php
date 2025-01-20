<?php

namespace App\Http\Controllers\Admin\Genre;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Genre\UpdateRequest;
use App\Models\Genre;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request)
    {
        // Получаем валидированные данные
        $data = $request->validated();
        // Найти категорию по ID
        $genre = Genre::findOrFail($data['id']);

        // Обновить категорию с новыми данными
        $genre->update([
            'title' => $data['title']
        ]);

        return response()->json([
            'message' => 'Категория обновлена успешно',
            'data' => $genre
        ], 200);
    }
}
