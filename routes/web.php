<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;

// Auth Routes (tanpa verifikasi email)
Auth::routes();

// Public Routes - mengubah home ke index
Route::get('/', function () {
    return view('home');
})->name('home');

// Competition Routes (renamed from products)
Route::controller(ProductController::class)->group(function () {
    Route::get('/competitions', 'index')->name('products.index');
    Route::get('/competitions/{id}', 'show')->name('products.show');
});

// Registration Process Routes
Route::controller(CartController::class)->group(function () {
    Route::get('/registration', 'index')->name('cart.index');
    Route::post('/registration/add', 'add')->name('cart.add');
    Route::patch('/registration/update', 'update')->name('cart.update');
    Route::delete('/registration/remove', 'remove')->name('cart.remove');
});

// Checkout Routes - Requires Authentication
Route::middleware('auth')->group(function () {
    // Checkout
    Route::controller(CheckoutController::class)->prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/process', 'process')->name('process');
    });
    
    // Orders
    Route::controller(OrderController::class)->prefix('registrations')->name('orders.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{id}', 'show')->name('show');
    });
    
    // Payment - Fixed routes
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::get('/finish', [PaymentController::class, 'finish'])->name('finish');
        Route::get('/unfinish', [PaymentController::class, 'unfinish'])->name('unfinish');
        Route::get('/error', [PaymentController::class, 'error'])->name('error');
        Route::get('/{id}', [PaymentController::class, 'show'])->name('show');
    });
    
    // Profile
    Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::put('/', 'update')->name('update');
    });
    
    // Participant Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Participant\DashboardController::class, 'index'])
        ->middleware('check_permission:peserta')
        ->name('dashboard');
});

// Payment Notification (Midtrans webhook) - No auth required
Route::post('/payment/notification', [PaymentController::class, 'notification'])->name('payment.notification');

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'check_permission:admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Resource Routes for Admin
    Route::resources([
        'competitions' => App\Http\Controllers\Admin\CompetitionController::class,
        'participants' => App\Http\Controllers\Admin\ParticipantController::class,
        'judges' => App\Http\Controllers\Admin\JudgeController::class,
        'registrations' => App\Http\Controllers\Admin\RegistrationController::class,
        'submissions' => App\Http\Controllers\Admin\SubmissionController::class,
        'evaluations' => App\Http\Controllers\Admin\EvaluationController::class,
        'payments' => App\Http\Controllers\Admin\PaymentController::class,
    ]);
    
    Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
});

// Judge Routes
Route::prefix('judge')->middleware(['auth', 'check_permission:juri'])->name('judge.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Judge\DashboardController::class, 'index'])->name('dashboard');
    
    // Resource Routes for Judge
    Route::resources([
        'competitions' => App\Http\Controllers\Judge\CompetitionController::class,
        'submissions' => App\Http\Controllers\Judge\SubmissionController::class,
        'evaluations' => App\Http\Controllers\Judge\EvaluationController::class,
    ]);
});

// Super Admin (KIW) Routes
Route::prefix('kiw')->middleware(['auth', 'check_role:kiw'])->name('kiw.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Kiw\DashboardController::class, 'index'])->name('dashboard');
    
    // Resource Routes for Super Admin
    Route::resources([
        'users' => App\Http\Controllers\Kiw\UserController::class,
        'roles' => App\Http\Controllers\Kiw\RoleController::class,
        'competitions' => App\Http\Controllers\Kiw\CompetitionController::class,
        'evaluations' => App\Http\Controllers\Kiw\EvaluationController::class,
        'submissions' => App\Http\Controllers\Kiw\SubmissionController::class,
        'payments' => App\Http\Controllers\Kiw\PaymentController::class,
    ]);
    
    // Tambahkan route baru untuk kriteria penilaian kompetisi
    Route::controller(App\Http\Controllers\Kiw\CompetitionController::class)->prefix('competitions')->name('competitions.')->group(function () {
        Route::get('/{competition}/criteria', 'criteria')->name('criteria');
        Route::post('/{competition}/criteria', 'storeCriteria')->name('criteria.store');
        Route::put('/{competition}/criteria/{criterion}', 'updateCriteria')->name('criteria.update');
        Route::delete('/{competition}/criteria/{criterion}', 'destroyCriteria')->name('criteria.destroy');
    });
    
    Route::get('/reports', [App\Http\Controllers\Kiw\ReportController::class, 'index'])->name('reports.index');
    Route::get('/settings', [App\Http\Controllers\Kiw\SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [App\Http\Controllers\Kiw\SettingController::class, 'update'])->name('settings.update');
});

// Debug Midtrans Route (only in non-production environment)
if (!app()->environment('production')) {
    Route::get('/debug-midtrans', function () {
        \App\Helpers\MidtransHelper::initMidtransConfig();
        
        return response()->json([
            'server_key' => \Midtrans\Config::$serverKey,
            'client_key' => \Midtrans\Config::$clientKey,
            'is_production' => \Midtrans\Config::$isProduction,
            'env_values' => [
                'server_key' => config('midtrans.server_key'),
                'client_key' => config('midtrans.client_key'),
                'is_production' => config('midtrans.is_production'),
            ]
        ]);
    })->middleware('auth');
}