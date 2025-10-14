<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\HasMany;


class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = products::all();
        return response()->json([
            'message' => 'Products retrieved successfully',
            'products' => $products,
        ]);
    }

    //Get Product by Category Id
    public function getProductByCategoryId($id){
        $category = Category::find($id);
        
        if (!$category) {
            return response()->json([
                'message' => 'Category not found',
            ], 404);
        }
        
        $products = $category->products()->get();

        return response()->json([
            'message' => 'Products retrieved successfully',
            'products' => $products,
        ]);
    }

    //Search All Product
    public function searchProduct(Request $request){
        $request->validate([
            'search' => 'required|max:255',
        ]);

        $products = Products::where('name', 'like', '%'.$request->search.'%')->get();

        return response()->json([
            'products' => $products
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'image' => 'required',
        ]);

        $imagePath = null;
        if($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = Storage::disk('public')->putfile('products', $image);
        }

        $products = products::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imagePath,
            'category_id' => $category->id,
        ]);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $products,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, products $product)
    {
        $updateData = [
            'name' => $request->name ?? $product->name,
            'description' => $request->description ?? $product->description,
            'price' => $request->price ?? $product->price,
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $updateData['image'] = $path;
        }

        $product->update($updateData);

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(products $product)
    {
        $product->delete();
        return response()->json([
            'message' => 'Product deleted successfully',
            'product' => $product,
        ]);
    }
}
