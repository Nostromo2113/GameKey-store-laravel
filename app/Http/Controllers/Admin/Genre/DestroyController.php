<?php

namespace App\Http\Controllers\Admin\Genre;

use App\Http\Controllers\Controller;
use App\Models\Genre;

class DestroyController extends Controller
{
    public function __invoke(Genre $genre)
    {
        $genre->delete();
        return response()->json('Genre removed', 200);
    }
}
