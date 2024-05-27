<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route::get('/stripe', [StripeController::class, 'index'])->name('stripe.index');
// Route::post('/stripe/checkout', [StripeController::class, 'checkout'])->name('checkout');
// Route::get('/stripe/success', [StripeController::class, 'success'])->name('checkout.success');
// Route::get('/stripe/cancel', [StripeController::class, 'cancel'])->name('checkout.cancel');

//store routes
Route::get('/store', [ProductController::class, 'index'])->name('products.index');
Route::post('/store/checkout', [ProductController::class, 'checkout'])->name('checkout');

require __DIR__.'/auth.php';
