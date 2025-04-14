<?php

namespace App\Http\Controllers\Admin\Genre;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Genre\UpdateRequest;
use App\Models\Genre;

class UpdateController extends Controller
{
    public function __invoke(Genre $genre, UpdateRequest $request)
    {
        $data = $request->validated();

        $genre->update([
            'title' => $data['title']
        ]);

        return response()->json([
            'message' => 'Категория успешно обновлена',
            'data'    => $genre
        ], 200);
    }
}
