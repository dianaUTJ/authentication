<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;

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
Route::get('/store', [ProductController::class, 'index'])->name('products.index');
Route::get('/store/checkout', [ProductController::class, 'cart'])->name('cart');
Route::post('/store/checkout', [ProductController::class, 'checkout'])->name('checkout');
Route::get('/store/success', [ProductController::class, 'success'])->name('checkout.success');
Route::get('/store/cancel', [ProductController::class, 'cancel'])->name('checkout.cancel');

Route::get('/charge', function () {
    return view('charge');
});

Route::post('/charge', function (Request $request) {
    // Set your Stripe API key.
    \Stripe\Stripe::setApiKey("pk_test_51PK21y03gK3LJhEqnfmGeuH7Rq7MLy51m5ZI9SUzrWekRizW7UKm3LAy4KnYdvbr1A9sAaLLIBQfWehqIE10er84007lIzRSio");

    // Get the payment amount and email address from the form.
    $amount = $request->input('amount') * 100;
    $email = $request->input('email');

    // Create a new Stripe customer.
    $customer = \Stripe\Customer::create([
        'email' => $email,
        'source' => $request->input('stripeToken'),
    ]);

    // Create a new Stripe charge.
    $charge = \Stripe\Charge::create([
        'customer' => $customer->id,
        'amount' => $amount,
        'currency' => 'usd',
    ]);

    // Display a success message to the user.
    return 'Payment successful!';
});
Route::post('/addToCart', [ProductController::class, 'addToCart'])->name('addToCart');

require __DIR__.'/auth.php';
