<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\StoreRequest;
use App\Models\Product;
use App\Models\TechnicalRequirement;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        try {
            $data = $request->validated();
            $categoryId = $data['category'];
            $genres = $data['genres'];
            $data['technical_requirements']['is_recommended'] = 1;
            if (isset($data['file'])) {
                $data['file'] = Storage::disk('public')->put('uploads/products/preview_images', $data['file']);
            } else {
                $data['file'] = 'no image';
            }


            $product = Product::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'publisher' => $data['publisher'],
                'release_date' => $data['release_date'],
                'preview_image' => $data['file'],
                'price' => $data['price'],
                'category_id' => $categoryId,
                'is_published' => $data['is_published'],
                'amount' => 1
            ]);
            $data['technical_requirements']['product_id'] = $product['id'];

            TechnicalRequirement::create($data['technical_requirements']);


            $product->genres()->sync($genres);
            $product->load('category', 'technicalRequirements', 'genres');
            return response()->json([
                'message' => 'Продукт обновлен успешно',
                'data' => $product,
            ], 201);

        } catch (ModelNotFoundException $e) {
            // Обработка случая, когда продукт не найден
            return response()->json([
                'message' => 'Продукт не найден.',
                'error' => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            // Обработка всех остальных ошибок
            return response()->json([
                'message' => 'Произошла ошибка при добавлении продукта.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
