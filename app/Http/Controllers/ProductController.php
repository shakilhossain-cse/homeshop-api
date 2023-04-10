<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    //
    public function index()
    {
        $products = Product::latest()->paginate(6);
        return response()->json($products);
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        return response()->json($product);
    }

    public function store(Request $request)
    {
        function createUrlSlug($urlString)
        {
            $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $urlString);
            return $slug;
        }
        $product = new Product();
        $product->title = $request->title;
        $product->slug = createUrlSlug($request->title);
        $product->description = $request->description;
        $product->short_description = $request->short_description;
        $product->sku = $request->sku;
        $product->brand = $request->brand;
        $product->category = $request->category;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->discount_price = $request->discount_price;
        $product->images = $request->images;
        $product->sizes = $request->sizes;
        $product->save();
        return response()->json(["message" => "Product created successfully"], 201);
    }

    public function search(Request $request)
    {
        $categories = $request->input('categories', []);
        $brands = $request->input('brands', []);
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $size = $request->input('size');
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $sortBy = $request->input('sort_by');
        $query = Product::query();

        if (!empty($categories)) {
            $query->whereIn('category', $categories);
        }

        if (!empty($brands)) {
            $query->whereIn('brand', $brands);
        }

        if (!is_null($minPrice)) {
            $query->where('price', '>=', $minPrice);
        }

        if (!is_null($maxPrice)) {
            $query->where('price', '<=', $maxPrice);
        }

        if (!empty($size)) {
            $query->whereJsonContains('sizes', [$size]);
        }

        if (!empty($search)) {
            $query->where('name', 'like', "%$search%");
        }
        switch ($sortBy) {
            case 'low_to_high':
                $query->orderBy('price', 'asc');
                break;
            case 'high_to_low':
                $query->orderBy('price', 'desc');
                break;
            case 'latest':
                $query->latest();
                break;
            default:
                $query->inRandomOrder();
        }
        $products = $query->paginate($perPage, ['*'], 'page', $page);
        return response()->json($products);
    }


    public function filter()
    {
        $categories = Product::distinct('category')->pluck('category');
        $colors = Product::distinct('colors')->pluck('colors');
        $sizes =  ["m", "l", "xl", "xxl", "2xl"];

        $uniqueColors = collect($colors)
            ->flatten()
            ->unique()
            ->values();
        $data = [
            'categories' => $categories,
            'colors' => $uniqueColors,
            'sizes' => $sizes
        ];

        return response()->json($data);
    }

    public function destroy($productId)
    {
        $product = Product::findOrFail($productId);
        $product->delete();
        return response()->json(['message' => 'Resource deleted successfully']);
    }
}
