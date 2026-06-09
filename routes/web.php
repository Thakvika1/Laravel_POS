<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('products.index');
});

// Products
Route::resource('products', ProductController::class);

// Orders
Route::resource('orders', OrderController::class)->only(['index', 'create', 'store', 'show', 'destroy']);
Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
Route::get('orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
