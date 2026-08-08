<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::all();

        return $customers;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        $data = $request->validated();
        $customer = Customer::create($data);

        return response()->json([
            'message' => 'El cliente ha sido registrado correctamente.',
            'data' => $customer,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return response()->json([
            'data' => $customer,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $data = $request->validated();
        $customer->update($data);

        return response()->json([
            'message' => 'El cliente ha sido actualizado correctamente.',
            'data' => $customer,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        if (!$customer->is_active) {
               return response()->json([
                'message' => 'El cliente ya se encuentra inactivo.',
                'data' => $customer,
            ], 200);
        }

        $customer->update([
            'is_active' => false
        ]);

        return response()->json([
            'message' => 'El cliente ha sido inactivado correctamente.',
            'data' => $customer,
        ],200);
    }
}
