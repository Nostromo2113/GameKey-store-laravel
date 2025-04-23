<?php

namespace App\Services\Admin\Product;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductUpdater
{
    public function updateProduct(Product $product, array $data): Product
    {
        DB::beginTransaction();

        try {
            $file    = $this->overwriteFile($product, $data);
            $product = $this->fillProduct($product, $data, $file);
            $this->syncGenresForProduct($product, $data);
            $this->updateProductTechnicalRequirements($product, $data);

            DB::commit();

            return $product;

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Ошибка при обновлении продукта: ' . $e->getMessage());
        }
    }


    private function fillProduct(Product $product, array $data, string $file): Product
    {
        try {
            $product->fill([
                'title'         => $data['title'],
                'description'   => $data['description'],
                'publisher'     => $data['publisher'],
                'release_date'  => $data['release_date'],
                'preview_image' => $file,
                'price'         => $data['price'],
                'category_id'   => $data['category'],
                'is_published'  => $data['is_published'],
            ])->save();

            return $product;

        } catch (\Exception $e) {
            throw new \Exception('Ошибка при обновлении данных продукта: ' . $e->getMessage());
        }
    }


    private function overwriteFile(Product $product, array $data): string
    {
        $oldImagePath = $product->preview_image;

        $existsOldImagePage = Storage::disk('public')->exists($oldImagePath);

        if (isset($data['file'])) {

            // Удаляем старый файл, при необходимости
            if ($oldImagePath && $existsOldImagePage) {
                Storage::disk('public')->delete($oldImagePath);
            }

            $file = Storage::disk('public')->put('uploads/products/preview_images', $data['file']);

        } else {
            $file = $product->preview_image;
        }

        return $file;
    }

    private function syncGenresForProduct(Product $product, array $data): void
    {
        try {
            if (isset($data['genres']) && count($data['genres']) > 0) {
                $product->genres()->sync($data['genres']);
            } else {
                throw  new \Exception('Ошибка при получении жанров для обновления данных продукта');
            }
        } catch (\Exception $e) {
            throw new \Exception('Ошибка при синхронизации жанров с продуктом: ' . $e->getMessage());
        }
    }


    private function updateProductTechnicalRequirements(Product $product, $data): void
    {
        if (isset($data['technical_requirements'])) {
            try {
                $technicalRequirements = $product->technicalRequirements;
                $technicalRequirements->update($data['technical_requirements']);
            } catch (\Exception $e) {
                throw new \Exception('Ошибка при обновлении тех. требований продукта: ' . $e->getMessage());
            }
        } else {
            throw new \Exception('Ошибка при получении данных для обновления тех. требований продукта');
        }
    }
}
