<?php

namespace App\Http\Controllers;

use App\Models\cart_items;
use Illuminate\Http\Request;

class CartItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, cart_items $cart_item)
    {
        $cart_item->update([
            'quantity' => $request->quantity,
        ]);
        
        $cart_item->refresh();
        
        // Recalculate cart total
        $cart = $cart_item->cart;
        $cart->total = $cart->items()->get()->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        $cart->save();
        
        return response()->json([
            'message' => 'Cart item updated successfully',
            'cart_item' => $cart_item,
            'cart' => $cart,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(cart_items $cart_item)
    {
        $cart = $cart_item->cart;
        $cart_item->delete();
        
        // Recalculate cart total
        $cart->total = $cart->items()->get()->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        $cart->save();
        
        return response()->json([
            'message' => 'Cart item deleted successfully',
            'cart' => $cart,
        ], 200);
    }
}
