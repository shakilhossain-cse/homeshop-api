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
        $validatedData = $request->validate([
            'title' => 'required|max:255|unique:products,title,',
            'description' => 'required',
            'short_description' => 'required',
            'sku' => 'required',
            'brand' => 'required',
            'category' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'discount_price' => 'nullable|numeric',
            'images' => 'nullable|array',
            'sizes' => 'nullable|array',
        ]);

        $product = new Product();
        $product->title = $validatedData['title'];
        $product->slug = createUrlSlug($validatedData['title']);
        $product->description = $validatedData['description'];
        $product->short_description = $validatedData['short_description'];
        $product->sku = $validatedData['sku'];
        $product->brand = $validatedData['brand'];
        $product->category = $validatedData['category'];
        $product->price = $validatedData['price'];
        $product->quantity = $validatedData['quantity'];
        $product->discount_price = $validatedData['discount_price'];
        $product->images = $validatedData['images'];
        $product->sizes = $validatedData['sizes'];

        $product->save();

        return response()->json(["message" => "Product created successfully"], 201);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255|unique:products,title,' . $id,
            'description' => 'required',
            'short_description' => 'required',
            'sku' => 'required',
            'brand' => 'required',
            'category' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'discount_price' => 'nullable|numeric',
            'images' => 'nullable|array',
            'sizes' => 'nullable|array',
        ]);

        $product = Product::findOrFail($id);
        $product->title = $validatedData['title'];
        $product->slug = createUrlSlug($validatedData['title']);
        $product->description = $validatedData['description'];
        $product->short_description = $validatedData['short_description'];
        $product->sku = $validatedData['sku'];
        $product->brand = $validatedData['brand'];
        $product->category = $validatedData['category'];
        $product->price = $validatedData['price'];
        $product->quantity = $validatedData['quantity'];
        $product->discount_price = $validatedData['discount_price'];
        $product->images = $validatedData['images'];
        $product->sizes = $validatedData['sizes'];

        $product->save();

        return response()->json(["message" => "Product updated successfully"], 200);
    }




    public function search(Request $request)
    {
        $category = $request->input('categories');
        $brand = $request->input('brands');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $size = $request->input('size');
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $sortBy = $request->input('sort_by');
        $query = Product::query();

        $categories = explode(",", $category);
        $brands = explode(",", $brand);

        if (count($categories) >= 1 && $categories[0] !== '') {
            $query->whereIn('category', $categories);
        }
        if (count($brands) >= 1 && $brands[0] !== '') {
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
            $query->where('title', 'like', "%$search%");
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
        $brands = Product::distinct('brand')->pluck('brand');
        $sizes =  ["m", "l", "xl", "xxl", "2xl"];

        // $colors = Product::distinct('colors')->pluck('colors');
        // $uniqueColors = collect($colors)
        //     ->flatten()
        //     ->unique()
        //     ->values();
        $data = [
            'categories' => $categories,
            'brands' => $brands,
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

function createUrlSlug($urlString)
{
    $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $urlString);
    return $slug;
}
