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
            'order_product_id' // Пивот-таблица         // Внешниий ключ для получения activation key из пивот         // Для получения модели ключа
        );
    }

//    public function activationKeys()
//    {
//        return $this->belongsToMany(
//            ActivationKey::class,
//            'order_products_activation_keys', // Пивот-таблица
//            'order_product_id',              // Внешниий ключ для получения activation key из пивот
//            'activation_key_id'              // Для получения модели ключа
//        );
//    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
