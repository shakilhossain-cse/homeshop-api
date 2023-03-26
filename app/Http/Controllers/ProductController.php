<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //
    public function index()
    {
        $products = Product::paginate(6);
        return response()->json($products);
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        return response()->json($product);
    }

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
}
