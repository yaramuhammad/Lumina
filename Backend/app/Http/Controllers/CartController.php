<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function addItem(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'color_size_id' => 'required|exists:product_color_size,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }
        
        $product = Product::with('colors.sizes')->findOrFail($request->product_id);
        $availableColorSizeIds = $product->colors->flatMap(function ($color) {
            return $color->sizes->pluck('pivot.id')->filter();
        })->toArray();
        
        if (!in_array($request->color_size_id, $availableColorSizeIds)) {
            return response()->json(['message' => 'Invalid color size for the selected product'], 422);
        }

        $user->cartItems()->create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'product_color_size_id' => $request->color_size_id,
        ]);

        return response()->json(['message' => 'Item added to cart']);
    }



    public function viewCart(Request $request)
    {
        $user = $request->user();

        $cartItems = $user->cartItems()->with('product', 'productColorSize.productColor.images')->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 404);
        }

        return response()->json($cartItems, 200);
    }

    public function updateItem(Request $request, $itemId)
    {
        $user = $request->user();

        $cartItem = CartItem::where('id', $itemId)
            ->where('user_id', $user->id)
            ->firstOrFail();


        $newQuantity = $cartItem->quantity + $request->quantity;

        if ($newQuantity <= 0) {
            $cartItem->delete();
        } else {
            $cartItem->update(['quantity' => $newQuantity]);
        }

        return response()->json(['message' => 'Cart item updated']);
    }

    public function removeItem($itemId)
    {
        $user = request()->user();

        $cartItem = CartItem::where('id', $itemId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $cartItem->delete();
        return response()->json(['message' => 'Cart item removed']);
    }

    public function clearCart(Request $request)
    {
        $user = $request->user();

        $cartItems = $user->cartItems;

        if ($cartItems->isNotEmpty()) {
            foreach ($cartItems as $cartItem) {
                $cartItem->delete();
            }
            return response()->json(['message' => 'Cart cleared']);
        }

        return response()->json(['message' => 'Cart is already empty']);
    }
}
