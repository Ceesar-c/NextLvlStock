<?php

use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PurchaseController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\SupplierController;
use Illuminate\Support\Facades\Route;

Route::apiResource('categories', CategoryController::class);
Route::apiResource('brands', BrandController::class);
Route::apiResource('suppliers', SupplierController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('purchases', PurchaseController::class);
Route::apiResource('customers', CustomerController::class);
Route::apiResource('sales', SaleController::class);
