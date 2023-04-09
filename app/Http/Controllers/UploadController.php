<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Upload;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    //
    public function upload(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:1024',
        ]);

        $image = $request->image->store('public/image');
        $imageUrl = asset(str_replace('public/', 'storage/', $image));
        $imagePath = ["url" => $imageUrl];
        Upload::create(['image' => $imageUrl]);
        return response()->json($imagePath, 201);
    }
}
