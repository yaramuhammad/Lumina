<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Models\ProductColorSize;
use App\Models\Address;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {
        $user = auth()->user();
        $cartItems = CartItem::where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        $address = Address::where('id', $request->address_id)->where('user_id', $user->id)->first();
        if (!$address) {
            return response()->json(['message' => 'Invalid address'], 400);
        }

        DB::beginTransaction();

        try {
            $totalPrice = 0;

            foreach ($cartItems as $cartItem) {
                $productColorSize = ProductColorSize::find($cartItem->product_color_size_id);

                if ($cartItem->quantity > $productColorSize->quantity) {
                    return response()->json(['message' => 'Insufficient quantity for one or more items'], 400);
                }

                $totalPrice += $productColorSize->productColor->price * $cartItem->quantity;
            }
            $order = Order::create([
                'user_id' => $user->id,
                'address_id' => $request->address_id,
                'total_price' => $totalPrice,
                'payment_method' => $request->payment_method,
                'status' => 'Pending',
            ]);
            
            foreach ($cartItems as $cartItem) {
                $productColorSize = ProductColorSize::find($cartItem->product_color_size_id);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_color_size_id' => $cartItem->product_color_size_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $productColorSize->productColor->price,
                ]);

                $productColorSize->decrement('quantity', $cartItem->quantity);
                $cartItem->delete();
            }

            DB::commit();

            return response()->json(['message' => 'Order created successfully', 'order' => $order], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Order creation failed', 'error' => $e->getMessage()], 500);
        }
    }


    public function index(Request $request)
    {
        $user = auth()->user();
        return $user->orders;
    }
}
