<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicalRequirement extends Model
{
    use HasFactory;

    protected $guarded = false;
    protected $fillable = [
        'id',
        'platform',
        'os',
        'cpu',
        'gpu',
        'ram',
        'storage',
        'is_recommended',
        'product_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
