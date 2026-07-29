<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Models\Brand;
// use App\Models\Category;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::all();
        return $brands;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request)
    {
        $data = $request->validated();
        $brand = Brand::create($data);

        return response()->json([
            'message' => 'La marca ha sido registrada exitosamente.',
            'data' => $brand,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        return response()->json([
            'data' => $brand
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        $data = $request->validated();
        $brand->update($data);

        return response()->json([
            'message' => 'La marca ha sido actualizada correctamente.',
            'data' => $brand,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
       if(!$brand->is_active){
            return response()->json([
                'message' => 'La marca ya se encuentra inactiva.',
                'data' => $brand,
            ], 200);
        }

        $brand->update([
            'is_active' => false
        ]);

        return response()->json([
            'message' => 'La marca ha sido inactivada correctamente.',
            'data' => $brand,
        ], 200);
    }
}
