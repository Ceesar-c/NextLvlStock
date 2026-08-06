<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePurchaseRequest;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Purchase::with(['supplier', 'purchaseDetails.product'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePurchaseRequest $request)
    {
        $data = $request->validated();

        $purchase = DB::transaction(function () use ($data) {
            $latestPurchase = Purchase::lockForUpdate()->latest('id')->first();

            $nextConsecutive = $latestPurchase ? (int) str($latestPurchase->purchase_number)->after('-')->toString() + 1 : 1;
            $consecutive = 'PO-' . str($nextConsecutive)->padLeft(6, '0');
            
            $total = collect($data['details'])
            ->sum(fn ($detail) => $detail['quantity'] * $detail['unit_price']);
            
            $purchase = Purchase::create([
                'purchase_number' => $consecutive,
                'supplier_id' => $data['supplier_id'],
                'purchase_date' => $data['purchase_date'],
                'total' => $total,
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($data['details'] as $detail) {
                $product = Product::findOrFail($detail['product_id']);

                if (!$product->is_active) {
                    throw ValidationException::withMessages([
                        'product_id' => [
                            'No es posible comprar un producto inactivo.'
                        ]
                    ]);
                }

                $subtotal = $detail['quantity'] * $detail['unit_price'];

                $purchase->purchaseDetails()->create([
                    'product_id' => $detail['product_id'],
                    'quantity' => $detail['quantity'],
                    'unit_price' => $detail['unit_price'],
                    'subtotal' => $subtotal
                ]);

                $product->increment('stock', $detail['quantity']);
            }

            return $purchase;
        });

        return response()->json([
            'message' => 'La compra ha sido registrada correctamente.',
            'data' => $purchase->load(['supplier', 'purchaseDetails.product'],),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        return $purchase->load([
            'supplier',
            'purchaseDetails.product'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return response()->json([
            'message' => 'Las compras no pueden modificarse.'
        ], 405);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response()->json([
            'message' => 'Las compras no pueden eliminarse.'
        ], 405);
    }
}
