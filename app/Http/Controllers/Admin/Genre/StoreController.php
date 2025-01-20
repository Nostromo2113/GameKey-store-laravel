<?php

namespace App\Http\Controllers\Admin\Genre;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Genre\StoreRequest;
use App\Models\Genre;

class StoreController extends Controller
{

    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        // Проверяем, существует ли категория с таким названием
        $genre = Genre::where('title', $data['title'])->first();

        if ($genre) {
            // Возвращаем статус 409, если категория уже существует
            return response()->json([
                'message' => 'Genre already exists'
            ], 409);
        }

        // Если категория не существует, создаем её
        $newGenre = Genre::create($data);

        // Возвращаем успешный ответ с данными и статусом 201
        return response()->json([
            'message' => 'Genre created successfully',
            'data' => $newGenre
        ], 201);
    }
}
