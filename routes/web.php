<?php

use App\Filament\Pages\Shop;
use App\Filament\Resources\ProductResource;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\ProductController;
use App\Livewire\ListProducts;
use Illuminate\Http\Request;
use App\Livewire\ViewProduct;

 Route::redirect('/', '/admin/login');

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

Route::get('/storeTest', function () {
    return Inertia::render('storeTest', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

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
// Route::get('/store', [ProductController::class, 'index'])->name('products.index');
// Route::get('/store/checkout', [ProductController::class, 'cart'])->name('cart');
// Route::post('/store/checkout', [ProductController::class, 'checkout'])->name('checkout');
// Route::get('/store/success', [ProductController::class, 'success'])->name('checkout.success');
// Route::get('/store/cancel', [ProductController::class, 'cancel'])->name('checkout.cancel');


Route::post('/addToCart', [ProductController::class, 'addToCart'])->name('addToCart');

Route::post('/webhook', [StripeController::class, 'stripeWebhook'])->name('webhook');

Route::get('/store', ListProducts::class);
Route::get('/store/products/{record}', ViewProduct::class)->name('products.show');

require __DIR__.'/auth.php';
