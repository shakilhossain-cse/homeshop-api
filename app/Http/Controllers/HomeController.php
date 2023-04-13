<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index()
    {
        $recentProducts = Product::latest()->take(4)->get();
        $randomProducts = Product::inRandomOrder()->take(4)->get();
        $categories = Product::distinct('category')->pluck('category');
        return response()->json([
            'categories' => $categories,
            'recentProducts' => $recentProducts,
            'randomProducts' => $randomProducts
        ]);
    }
}
