<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivationKey extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'key', 'product_id', 'order_product_id'
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
