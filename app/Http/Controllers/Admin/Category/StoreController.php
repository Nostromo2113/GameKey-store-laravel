<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\StoreRequest;
use App\Models\Category;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();

        $category = Category::where('title', $data['title'])->first();

        if ($category) {
            return response()->json([
                'message' => 'Category already exists'
            ], 409);
        }

        $newCategory = Category::create($data);

        return response()->json([
            'message' => 'Category created successfully',
            'data' => $newCategory
        ], 201);
    }
}
