<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function products()
    {
        return $this->belongsToMany(Product::class, 'genre_products', 'genre_id', 'product_id');
    }
}
