<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::with(['category', 'brand', 'supplier'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        $product = Product::create($data);

        return response()->json([
            'message' => 'El producto ha sido registrado correctamente.',
            'data' => $product,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'brand', 'supplier']);
        
        return response()->json([
            'data' => $product,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $product->update($data);

        return response()->json([
            'message' => 'El producto ha sido actualizado correctamente.',
            'data' => $product,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if(!$product->is_active){
            return response()->json([
                'message' => 'El producto ya se encuentra inactivo.',
                'data' => $product,
            ], 200);
        }

        $product->update([
            'is_active' => false
        ]);

        return response()->json([
            'message' => 'El producto ha sido inactivado correctamente.',
            'data' => $product,
        ], 200);
    }
}
