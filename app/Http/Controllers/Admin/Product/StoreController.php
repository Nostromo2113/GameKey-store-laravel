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
            $categoryId = $data['category']['id'];
            $genres = $data['genres'];
            $data['technical_requirements']['is_recommended'] = 1;
            if (isset($data['preview_image'])) {
                $data['preview_image'] = Storage::disk('public')->put('uploads/products/preview_images', $data['preview_image']);
            } else {
                $data['preview_image'] = 'no image';
            }


            $product = Product::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'publisher' => $data['publisher'],
                'release_date' => $data['release_date'],
                'preview_image' => $data['preview_image'],
                'price' => $data['price'],
                'amount' => $data['amount'],
                'category_id' => $categoryId,
                'is_published' => $data['is_published'],
            ]);
            $data['technical_requirements']['product_id'] = $product['id'];

            TechnicalRequirement::create($data['technical_requirements']);


            $product->genres()->sync($genres);
            $product->load('category', 'technicalRequirements.php', 'genres');
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
                'message' => 'Произошла ошибка при обновлении продукта.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
