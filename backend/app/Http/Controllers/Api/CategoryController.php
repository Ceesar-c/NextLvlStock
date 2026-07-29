<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return $categories;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();
        $category = Category::create($data);

        return response()->json([
            'message' => 'Categoría creada correctamente.',
            'data' => $category,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return response()->json([
            'data' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = $request->validated();
        $category->update($data);

        return response()->json([
            'message' => 'La categoría  ha sido actualizada correctamente.',
            'data' => $category,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if(!$category->is_active){
            return response()->json([
                'message' => 'La categoría ya se encuentra inactiva.',
                'data' => $category,
            ], 200);
        }

        $category->update([
            'is_active' => false
        ]);

        return response()->json([
            'message' => 'La categoría ha sido inactivada correctamente.',
            'data' => $category,
        ], 200);
    }
}
