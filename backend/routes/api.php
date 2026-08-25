<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PurchaseController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::post('/login', [AuthController::class, 'login']);

// Rutas autenticadas
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware(['active', 'role.active'])->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::patch('/me/password', [AuthController::class, 'updatePassword']);

        // Products
        Route::controller(ProductController::class)->group(function () {
            Route::get('/products', 'index')->middleware('permission:products.view');
            Route::get('/products/{product}', 'show')->middleware('permission:products.view');
            Route::post('/products', 'store')->middleware('permission:products.create');
            Route::match(['put', 'patch'], '/products/{product}', 'update')->middleware('permission:products.update');
            Route::delete('/products/{product}', 'destroy')->middleware('permission:products.deactivate');
        });

        // Categories
        Route::controller(CategoryController::class)->group(function () {
            Route::get('/categories', 'index')->middleware('permission:categories.view');
            Route::get('/categories/{category}', 'show')->middleware('permission:categories.view');
            Route::post('/categories', 'store')->middleware('permission:categories.create');
            Route::match(['put', 'patch'], '/categories/{category}', 'update')->middleware('permission:categories.update');
        });

        // Brands
        Route::controller(BrandController::class)->group(function () {
            Route::get('/brands', 'index')->middleware('permission:brands.view');
            Route::get('/brands/{brand}', 'show')->middleware('permission:brands.view');
            Route::post('/brands', 'store')->middleware('permission:brands.create');
            Route::match(['put', 'patch'], '/brands/{brand}', 'update')->middleware('permission:brands.update');
        });

        // Customers
        Route::controller(CustomerController::class)->group(function () {
            Route::get('/customers', 'index')->middleware('permission:customers.view');
            Route::get('/customers/{customer}', 'show')->middleware('permission:customers.view');
            Route::post('/customers', 'store')->middleware('permission:customers.create');
            Route::match(['put', 'patch'], '/customers/{customer}', 'update')->middleware('permission:customers.update');
        });

        // Suppliers
        Route::controller(SupplierController::class)->group(function () {
            Route::get('/suppliers', 'index')->middleware('permission:suppliers.view');
            Route::get('/suppliers/{supplier}', 'show')->middleware('permission:suppliers.view');
            Route::post('/suppliers', 'store')->middleware('permission:suppliers.create');
            Route::match(['put', 'patch'], '/suppliers/{supplier}', 'update')->middleware('permission:suppliers.update');
        });

        // Purchases
        Route::controller(PurchaseController::class)->group(function () {
            Route::get('/purchases', 'index')->middleware('permission:purchases.view');
            Route::get('/purchases/{purchase}', 'show')->middleware('permission:purchases.view');
            Route::post('/purchases', 'store')->middleware('permission:purchases.create');
            Route::match(['put', 'patch'], '/purchases/{purchase}', 'update')->middleware('permission:purchases.update');
        });

        // Sales
        Route::controller(SaleController::class)->group(function () {
            Route::get('/sales', 'index')->middleware('permission:sales.view');
            Route::get('/sales/{sale}', 'show')->middleware('permission:sales.view');
            Route::post('/sales', 'store')->middleware('permission:sales.create');
            Route::match(['put', 'patch'], '/sales/{sale}', 'update')->middleware('permission:sales.update');
        });

        // Users
        Route::controller(UserController::class)->group(function () {
            Route::get('/users', 'index')->middleware('permission:users.view');
            Route::get('/users/{user}', 'show')->middleware('permission:users.view');
            Route::post('/users', 'store')->middleware('permission:users.create');
            Route::match(['put', 'patch'], '/users/{user}', 'update')->middleware('permission:users.update');
            Route::delete('/users/{user}', 'destroy')->middleware('permission:users.deactivate');
            Route::post('/users/{user}/activate', 'activate')->middleware('permission:users.activate');
        });
    });


});
