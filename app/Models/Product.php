<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use Filterable;
    use HasFactory;
    use SoftDeletes;
    protected $guarded = false;


    public function activationKeys()
    {
        return $this->hasMany(ActivationKey::class, 'product_id');
    }


    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function technicalRequirements()
    {
        return $this->hasOne(TechnicalRequirement::class, 'product_id');
    }
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'genre_products', 'product_id', 'genre_id');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_products', 'product_id', 'order_id')->withPivot('activation_key_id');
    }

    public function cart()
    {
        return $this->belongsToMany(Cart::class, 'cart_products', 'product_id', 'cart_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'product_id');
    }

}
