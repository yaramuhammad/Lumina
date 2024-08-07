<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function AddProductToWishlist(Request $request)
    {
        $productID = $request->productID;
        $user = Auth::user();
        if(!$user->wishlist()->where('product_id', $productID)->exists()) {
            $user->wishlist()->attach($productID);
            return response()->json(['message' => 'Product added to wishlist'], 200);
        }

        return response()->json(['message' => 'Product already added to wishlist'], 400);
    }

    public function getWishlist(Request $request)
    {
        $user = auth()->user();
        $wishlist = $user->wishlist;

        return response()->json($wishlist, 200);
    }


    public function removeFromWishlist(Request $request)
    {
        $user = auth()->user();
        $productID = $request->productID;
        
        if($user->wishlist()->where('product_id', $productID)->exists()) {
            $user->wishlist()->detach($productID);
            return response()->json(['message' => 'Product removed from wishlist'], 200);
        }
        return response()->json(['message' => 'Product was not in the wishlist'], 400);

    }
}
