<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivationKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'key', 'product_id', 'order_product_id'
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
