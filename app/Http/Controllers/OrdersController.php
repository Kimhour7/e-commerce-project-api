<?php

namespace App\Http\Controllers;

use App\Models\orders;
use App\Models\order_items;
use Illuminate\Http\Request;
use App\Models\carts;
use App\Models\cart_items;
use App\Models\products;

class OrdersController extends Controller
{
    public function checkout(Request $request){
        $user = auth()->user();
        $cart = carts::where('user_id', $user->id)->first();
        if($cart === null){
            return response()->json([
                'message' => 'No cart found',
            ], 404);
        }
        $cartItem = cart_items::where('cart_id', $cart->id)->get();
        if($cartItem === null){
            return response()->json([
                'message' => 'No cart items found',
            ], 404);
        }
        $order = orders::create([
            'user_id' => $user->id,
            'address_id' => $request->address_id,
            'total_amount' => $cart->total,
            'cart_id' => $cart->id,
            'status' => 'PENDING',
        ]);
        
        // Create order items from cart items
        foreach ($cartItem as $item) {
            order_items::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ]);
        }
        
        // Delete all cart items
        cart_items::where('cart_id', $cart->id)->delete();
        
        // Delete the cart
        $cart->delete();
        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order,
        ], 200);
    }

    public function index(){
        $user = auth()->user();
        $orders = orders::where('user_id', $user->id)->get();
        $orders->load('order_items.product');
        return response()->json([
            'orders' => $orders,
        ], 200);
    }
}
