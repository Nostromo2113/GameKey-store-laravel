<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'product_id', 'quantity'];


    public function activationKeys()
    {
        return $this->hasMany(
            ActivationKey::class,
            'order_product_id'
        );
    }


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
