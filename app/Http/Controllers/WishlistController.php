<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlist = Wishlist::where('user_id', '=', auth()->user()->id)->get();
        return response()->json($wishlist);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $wishlist = new Wishlist;
        $wishlist->user_id = auth()->user()->id;
        $wishlist->product_id = $request->input('product_id');
        $wishlist->save();

        return response()->json([
            'message' => 'Product added to wishlist!',
        ], 201);
    }

    public function destroy($id)
    {
        $wishlistItem = Wishlist::findOrFail($id);

        if ($wishlistItem->user_id !== auth()->user()->id) {
            return response()->json([
                'message' => 'Unauthorized action.',
            ], 403);
        }

        $wishlistItem->delete();

        return response()->json([
            'message' => 'Product removed from wishlist!',
        ]);
    }
}
