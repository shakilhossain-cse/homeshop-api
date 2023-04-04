<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    //
    public function upload(Request $request, $id)
    {
        // $product = Product::find($id);

        // if (!$product) {
        //     return response()->json(['message' => 'Product not found.'], 404);
        // }

        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|max:1024',
        ]);


        if ($request->hasFile('images')) {
            $images = $request->file('images');
            $urls = [];

            foreach ($images as $image) {
                $path = $image->store('public/images');
                $url = Storage::url($path);
                $upload = new Upload(['path' => $path]);
                $upload->filename = $image;
                // $product->images()->save($upload);
                $upload->save();
                $urls[] = $url;
            }

            return response()->json(['urls' => $urls]);
        }

        return response()->json(['message' => 'No images found.'], 400);
    }
}
