<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;

// Home Route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth Routes
Auth::routes();

// Product Routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

// Checkout Routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

// Order Routes
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');

// Payment Routes
Route::get('/payment/{id}', [PaymentController::class, 'show'])->name('payment.show');
Route::post('/payment/notification', [PaymentController::class, 'notification'])->name('payment.notification');
Route::get('/payment/finish', [PaymentController::class, 'finish'])->name('payment.finish');
Route::get('/payment/unfinish', [PaymentController::class, 'unfinish'])->name('payment.unfinish');
Route::get('/payment/error', [PaymentController::class, 'error'])->name('payment.error');

// Debug Midtrans Route
Route::get('/debug-midtrans', function () {
    \App\Helpers\MidtransHelper::initMidtransConfig();
    
    $debug = [
        'server_key' => \Midtrans\Config::$serverKey,
        'client_key' => \Midtrans\Config::$clientKey,
        'is_production' => \Midtrans\Config::$isProduction,
        'env_values' => [
            'server_key' => config('midtrans.server_key'),
            'client_key' => config('midtrans.client_key'),
            'is_production' => config('midtrans.is_production'),
        ]
    ];
    
    return response()->json($debug);
})->middleware('auth');