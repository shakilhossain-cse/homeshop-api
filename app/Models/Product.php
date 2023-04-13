<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'short_description',
        'sku',
        'brand',
        'category',
        'price',
        'quantity',
        'discount_price',
        'images',
        'sizes',
    ];

    protected $casts = [
        'sizes' => 'json',
        'images' => 'json'
    ];


    public function wishlistedBy()
    {
        return $this->hasMany(Wishlist::class);
    }
}
