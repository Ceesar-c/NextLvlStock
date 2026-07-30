<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\Supplier;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::all();
        return $suppliers;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupplierRequest $request)
    {
        $data = $request->validated();
        $supplier = Supplier::create($data);

        return response()->json([
            'message' => 'El proveedor ha sido registrado correctamente.',
            'data' => $supplier,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        return response()->json([
            'data' => $supplier
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $data = $request->validated();
        $supplier->update($data);

        return response()->json([
            'message' => 'El proveedor ha sido actualizado con éxito.',
            'data' => $supplier,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        if(!$supplier->is_active){
            return response()->json([
                'message' => 'El proveedor ya se encuentra inactivo.',
                'data' => $supplier,
            ], 200);
        }

        $supplier->update([
            'is_active' => false
        ]);

        return response()->json([
            'message' => 'El proveedor ha sido inactivado correctamente.',
            'data' => $supplier,
        ], 200);
    }
}
