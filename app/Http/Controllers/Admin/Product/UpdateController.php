<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\UpdateRequest;
use App\Models\Product;
use App\Models\TechnicalRequirement;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Support\Facades\Storage;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request)
    {
        try {
            $data = $request->validated();
            $product = Product::findOrFail($data['id']);
            $categoryId = $data['category']['id'];
            $genres = $data['genres'];

            // Получаем путь к старому изображению
            $oldImagePath = $product->preview_image; // Это будет относительный путь

            // Удаляем старое изображение
            if ($oldImagePath && Storage::disk('public')->exists($oldImagePath) && isset($data['preview_image'])) {
                // Удаляем файл
                Storage::disk('public')->delete($oldImagePath);
            }
            if (isset($data['file'])) {
                $data['file'] = Storage::disk('public')->put('uploads/products/preview_images', $data['file']);
            } else {
                $data['file'] = $product->preview_image;
            }


            $product->fill([
                'title' => $data['title'],
                'description' => $data['description'],
                'publisher' => $data['publisher'],
                'release_date' => $data['release_date'],
                'preview_image' => $data['file'],
                'price' => $data['price'],
                'amount' => $data['amount'],
                'category_id' => $categoryId,
                'is_published' => $data['is_published'],
            ])->save();

            if ($genres && is_array($genres)) {
                $product->genres()->sync($genres);
            } else {
                return response()->json(['message' => 'An error occurred while binding genres'], 500);
            }

            if ($data['technical_requirements'] && is_array($data['technical_requirements'])) {
                try {
                    // Получаем связанные технические требования
                    $technicalRequirements = TechnicalRequirement::findOrFail($data['technical_requirements']['id']);

                    // Пытаемся обновить запись
                    $technicalRequirements->update($data['technical_requirements']);
                } catch (Exception $e) {
                    return response()->json(['message' => 'Error updating technical specifications', 'error' => $e->getMessage()], 500);
                }
            } else {
                return response()->json(['message' => 'Incorrect technical specifications data'], 400);
            }
            $product->load('category', 'technicalRequirements', 'genres');
            return response()->json([
                'message' => 'Product updated successfully',
                'data' => $product,
            ], 200);

        } catch (ModelNotFoundException $e) {
            // Обработка случая, когда продукт не найден
            return response()->json([
                'message' => 'Product not found.',
                'error' => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            // Обработка всех остальных ошибок
            return response()->json([
                'message' => 'An error occurred while updating the product',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
