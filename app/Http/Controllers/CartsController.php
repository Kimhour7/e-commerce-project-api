<?php

namespace App\Http\Controllers;

use App\Models\carts;
use App\Models\cart_items;
use App\Models\products;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CartsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $cart = carts::where('user_id', $user->id)->first();
        
        if($cart === null){
            return response()->json([
                'message' => 'No cart found',
            ], 404);
        }

        $cartItem = $cart->items()->get();
        if($cartItem->isEmpty()){
            return response()->json([
                'message' => 'No cart items found',
            ], 404);
        }
        
        return response()->json([
            'cart' => $cart,
            'cart_items' => $cartItem,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'product_id' => 'required',
                'quantity' => 'required',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        $user = auth()->user();
        $product = products::find($request->product_id);

        $cart = carts::firstOrCreate([
            'user_id' => $user->id,
            'status' => 'PENDING',
        ], [
            'total' => 0,
        ]);

        $item = $cart->items()->where('product_id', $product->id)->where('cart_id', $cart->id)->first();

        if ($item) {
            $item->quantity += $request->quantity;
            $item->save();
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->price,
            ]);
        }

        // Recalculate cart total from all items
        $cart->total = $cart->items()->get()->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        $cart->save();

        return response()->json([
            'message' => 'Cart created successfully',
            'cart' => $cart,
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, carts $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(carts $carts)
    {
        //
    }
}
