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

            $file    = $this->overwriteFile($product, $data);
            $product = $this->fillProduct($product, $data, $file);
            $this->syncGenresForProduct($product, $data);
            $this->updateProductTechnicalRequirements($product, $data);

            DB::commit();

            return $product;
    }


    private function fillProduct(Product $product, array $data, string $file): Product
    {
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
                $product->genres()->sync($data['genres']);
    }


    private function updateProductTechnicalRequirements(Product $product, $data): void
    {
                $technicalRequirements = $product->technicalRequirements;
                $technicalRequirements->update($data['technical_requirements']);
    }
}
