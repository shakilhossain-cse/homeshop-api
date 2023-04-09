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


    public function rules()
    {
        return [
            'title' => 'required|string',
            'description' => 'required|string',
            'short_description' => 'nullable|string',
            'sku' => 'required|string',
            'brand' => 'required|string',
            'category' => 'required|string',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
            'discount_price' => 'nullable|numeric',
            'images' => 'array',
            'sizes.*' => 'string',
            'sizes' => 'array',
            'sizes.*' => 'in:m,l,xl,xxl,2xl',
            'colors' => 'array',
            'colors.*' => 'in:red,blue,black,white,green',
        ];
    }
}
