<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Sale::with(['customer', 'saleDetails.product'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSaleRequest $request)
    {
        $data = $request->validated();

        $sale = DB::transaction(function () use($data) {
            $latestSale = Sale::lockForUpdate()->latest('id')->first();

            $nextConsecutive = $latestSale ? (int) str($latestSale->sale_number)->after('-')->toString() + 1 : 1;
            $consecutive = 'SO-' . str($nextConsecutive)->padLeft(6, '0');

            $details = [];
            $total = 0;

            foreach($data['details'] as $detail){
                $product = Product::lockForUpdate()->findOrFail($detail['product_id']);

                if (!$product->is_active) {
                    throw ValidationException::withMessages([
                        'product_id' => [
                            'No es posible vender un producto inactivo.'
                        ]
                    ]);
                }

                if($product->stock < $detail['quantity']){
                    throw ValidationException::withMessages([
                        'product_id' => [
                            'Stock insuficiente para realizar la venta.'
                        ]
                    ]);
                }

                $subtotal = $detail['quantity'] * $product->sale_price;
                $total += $subtotal;

                $details[] = [
                    'product' => $product,
                    'quantity' => $detail['quantity'],
                    'unit_price' => $product->sale_price,
                    'subtotal' => $subtotal,
                ];
            }

            $sale = Sale::create([
                'sale_number' => $consecutive,
                'customer_id' => $data['customer_id'],
                'sale_date' => $data['sale_date'],
                'status' => 'completed',
                'total' => $total,
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($details as $detail) {
                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $detail['product']->id,
                    'quantity' => $detail['quantity'],
                    'unit_price' => $detail['unit_price'],
                    'subtotal' => $detail['subtotal'],
                ]);

                $detail['product']->decrement('stock', $detail['quantity']);
            }

            return $sale;
        });

        return response()->json([
            'message' => 'La venta ha sido registrada correctamente.',
            'data' => $sale,
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        $sale->load([
            'customer',
            'saleDetails.product',
        ]);

        return response()->json([
            'data' => $sale,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSaleRequest $request, Sale $sale)
    {
        if ($sale->status === 'cancelled') {
            return response()->json([
                'message' => 'La venta cancelada no puede ser modificada.',
                'data' => $sale,
            ], 200);
        }

        $data = $request->validated();
        $sale->update($data);

        return response()->json([
            'message' => 'Las notas de la venta han sido actualizadas correctamente.',
            'data' => $sale,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        if ($sale->status === 'cancelled') {
            return response()->json([
                'message' => 'La venta ya se encuentra cancelada.',
                'data' => $sale,
            ], 200);
        }

        DB::transaction(function () use ($sale) {

            $sale->load('saleDetails');

            foreach ($sale->saleDetails as $detail) {
                $product = Product::lockForUpdate()->findOrFail($detail->product_id);

                $product->increment(
                    'stock',
                    $detail->quantity
                );
            }

            $sale->update([
                'status' => 'cancelled',
            ]);
        });

        return response()->json([
            'message' => 'La venta ha sido cancelada correctamente.',
            'data' => $sale,
        ], 200);
    }
}
