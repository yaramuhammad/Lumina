<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addItem(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'color_id' => 'required|exists:product_colors,id',
        ]);

        $product = Product::with('colors')->findOrFail($request->product_id);
        $availableColorIds = $product->colors->pluck('id')->toArray();

        if (! in_array($request->color_id, $availableColorIds)) {
            return response()->json(['message' => 'Invalid color for the selected product'], 422);
        }

        $cart = $user->cart ?? $user->cart()->create();
        $cart->items()->create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'product_color_id' => $request->color_id,
        ]);

        return response()->json(['message' => 'Item added to cart']);
    }

    public function viewCart(Request $request)
    {
        $user = $request->user();

        $cart = $user->cart;

        if (! $cart) {
            return response()->json(['message' => 'Cart is empty'], 404);
        }

        // Eager load product, color, and images
        $cartItems = $cart->items()->with('product', 'product_color.images')->get();

        $cartDetails = $cartItems->map(function ($item) {
            return [
                'item_id' => $item->id,
                'quantity' => $item->quantity,
                'product' => [
                    'id' => $item->product->id,
                    'name' => $item->product->name,
                    'price' => $item->product->price,
                ],
                'color' => [
                    'id' => $item->product_color->id,
                    'name' => $item->product_color->name,
                    'images' => $item->product_color->images->pluck('url'),
                ],
            ];
        });

        return response()->json($cartDetails, 200);
    }

    public function updateItem(Request $request, $itemId)
    {
        $user = $request->user();

        $cartItem = CartItem::where('id', $itemId)->whereHas('cart', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->firstOrFail();

        $newQuantity = $cartItem->quantity + $request->quantity;
        if ($newQuantity <= 0) {
            $cartItem->delete();
            if ($cartItem->cart->items()->count() === 0) {
                $cartItem->cart->delete();
            }
        } else {
            $cartItem->update([
                'quantity' => $newQuantity,
            ]);
        }

        return response()->json(['message' => 'Cart item updated']);
    }

    public function removeItem($itemId)
    {
        $user = request()->user();

        $cartItem = CartItem::where('id', $itemId)->whereHas('cart', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->firstOrFail();

        $cartItem->delete();
        if ($cartItem->cart->items()->count() === 0) {
            $cartItem->cart->delete();
        }

        return response()->json(['message' => 'Cart item removed']);
    }

    public function clearCart(Request $request)
    {
        $user = $request->user();

        $cart = $user->cart;

        if ($cart) {
            $cart->delete();

            return response()->json(['message' => 'Cart cleared']);
        }

        return response()->json(['message' => 'Cart is already empty']);
    }
}
