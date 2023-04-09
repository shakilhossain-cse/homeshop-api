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

    // title
    // description
    // short_description
    // sku
    // brand
    // category
    // price
    // quantity
    // discount_price
    // images
    // sizes

    public function search(Request $request)
    {

        // $jobTitle, $location, $jobtype, $category, $joblevel, $range
        $product_title = $request->input('query');
        $product_category = $request->input('category');
        $product_color = $request->input('colors');
        $product_size = $request->input('size');



        $jobs = Product::where('title', 'LIKE', '%' . $product_title . '%');

        if ($product_color) {
            $jobs->where('colors', $product_color);
        }
        if ($product_category) {
            $jobs->where('category', "=", $product_category);
        }
        if ($product_size) {
            $jobs->where('size', "=", $product_size);
        }
        // if ($categories) {
        //     $jobs->where('category', "=", $categories);
        // }
        // if ($jobtype) {
        //     $jobs->where('jobType', "=", $jobtype);
        // }
        // if ($joblevel) {
        //     $jobs->where('jobLevel', '=', $joblevel);
        // }
        // if ($salary) {
        //     $money =
        //         str_replace('$', '', $salary);
        //         $strSplit = explode("-", $money);
        //     $minRange = $strSplit[0];
        //     $maxRange = $strSplit[1];
        //     $jobs->whereBetween('salary', [$minRange, $maxRange]);
        // }

        $jobData = $jobs->paginate(6);
        return response([
            'data' => $jobData,
        ], 200);
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
