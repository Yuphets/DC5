<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

// Dashboard route
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Resource routes
Route::resource('products', ProductController::class);
Route::resource('suppliers', SupplierController::class);
Route::resource('inventories', InventoryController::class);
Route::get('/simple', function () {
    return \Inertia\Inertia::render('Simple', ['message' => 'Hello']);
});