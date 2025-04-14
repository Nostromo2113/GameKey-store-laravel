<?php

namespace App\Services\Admin\Product;

use App\Models\Product;
use App\Models\TechnicalRequirement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductStoreService
{
    public function storeProduct($data)
    {
        DB::beginTransaction();
        try {
            $categoryId = $data['category'];

            $genres = $data['genres'];

            $file = $this->writeFile($data);

            $product = Product::create([
                'title'         => $data['title'],
                'description'   => $data['description'],
                'publisher'     => $data['publisher'],
                'release_date'  => $data['release_date'],
                'preview_image' => $file,
                'price'         => $data['price'],
                'category_id'   => $categoryId,
                'is_published'  => $data['is_published'],
            ]);

            TechnicalRequirement::create([
                ...$data['technical_requirements'],
                'product_id' => $product->id
            ]);

            $product->genres()->sync($genres);

            DB::commit();

            return $product;

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Ошибка при создании продукта: ' . $e->getMessage());
        }
    }

    private function writeFile(array $data): string
    {
        try {
            if (isset($data['file'])) {
                $file = Storage::disk('public')->put('uploads/products/preview_images', $data['file']);
            } else {
                $file = 'no image';
            }

            return $file;
        } catch (\Exception $e) {
            throw new \Exception('Ошибка при записи превью файла продукта: ' . $e->getMessage());
        }
    }
}
