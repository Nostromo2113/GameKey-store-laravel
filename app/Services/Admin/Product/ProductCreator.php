<?php

namespace App\Services\Admin\Product;

use App\Models\ActivationKey;
use App\Models\Product;
use App\Models\TechnicalRequirement;
use App\Services\Admin\ActivationKeyGeneratorService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductCreator
{
    private $activationKeyGenerator;

    public function __construct(ActivationKeyGeneratorService $activationKeyGeneratorService)
    {
        $this->activationKeyGenerator = $activationKeyGeneratorService;
    }
    public function storeProduct($data)
    {
        return DB::transaction(function () use ($data) {
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

            $this->activationKeyGenerator->generateForProduct($product->id, 50);

            return $product;
        });
    }

    private function writeFile(array $data): string
    {
            if (isset($data['file'])) {
                $file = Storage::disk('public')->put('uploads/products/preview_images', $data['file']);
            } else {
                $file = 'no image';
            }
            return $file;
    }
}
