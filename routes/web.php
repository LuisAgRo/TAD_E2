<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Models\Product;
use App\Models\Category;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FavoriteController;
use Illuminate\Support\Facades\Schedule;

Route::get('/orders/{id}/retry', [OrderController::class, 'retry'])->name('orders.retry');


Schedule::command('orders:cancel-expired')->everyFiveMinutes();

//resumen pedido
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::get('/checkout/success', [OrderController::class, 'showCheckoutSuccess'])->name('orders.checkout.success');
    Route::get('/checkout/cancel', [OrderController::class, 'showCheckoutCancel'])->name('orders.checkout.cancel');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/admin/orders', [OrderController::class, 'adminIndex'])->name('admin.orders');
    Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
}); 

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['es', 'en'])) {
        session(['locale' => $locale]);
    }
    return back();
})->name('lang.switch');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/info', [ProfileController::class, 'updateInfo'])->name('profile.updateInfo');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
    Route::post('/profile/addresses', [ProfileController::class, 'storeAddress'])->name('profile.storeAddress');
    Route::delete('/profile/addresses/{id}', [ProfileController::class, 'destroyAddress'])->name('profile.destroyAddress');
    Route::post('/profile/payment-methods', [ProfileController::class, 'storePaymentMethod'])->name('profile.storePaymentMethod');
    Route::delete('/profile/payment-methods/{id}', [ProfileController::class, 'destroyPaymentMethod'])->name('profile.destroyPaymentMethod');
});

Route::get('/', function () {
    $categories = Category::all();
    $products = Product::latest()->take(6)->get();
    return view('welcome', compact('categories', 'products'));
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Auth
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Productos públicos
Route::get('/products', [ProductsController::class, 'index'])->name('products.index');
Route::get('/categories', [CategoriesController::class, 'index'])->name('categories.index');
Route::get('/products/{id}', [ProductsController::class, 'show'])
    ->whereNumber('id')
    ->name('products.show');

Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/items/{product}', [CartController::class, 'store'])
        ->whereNumber('product')
        ->name('cart.items.store');
    Route::put('/cart/items/{cartItem}', [CartController::class, 'update'])
        ->whereNumber('cartItem')
        ->name('cart.items.update');
    Route::delete('/cart/items/{cartItem}', [CartController::class, 'destroy'])
        ->whereNumber('cartItem')
        ->name('cart.items.destroy');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
});

//Productos favoritos
Route::middleware('auth')->group(function () {
    Route::get('/favoritos', [FavoriteController::class, 'index'])->name('favorites.index');
    
    // Esta ruta servirá para añadir o quitar productos de la lista
    Route::post('/favoritos/{product}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

    // Añadir o quitar productos de la lista de favoritos
    Route::post('/favoritos/{product}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

});


// Solo admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/products/create', [ProductsController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductsController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [ProductsController::class, 'edit'])
        ->whereNumber('id')
        ->name('products.edit');
    Route::put('/products/{id}', [ProductsController::class, 'update'])
        ->whereNumber('id')
        ->name('products.update');
    Route::delete('/products/{id}', [ProductsController::class, 'destroy'])
        ->whereNumber('id')
        ->name('products.destroy');

    Route::get('/categories/create', [CategoriesController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoriesController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id}/edit', [CategoriesController::class, 'edit'])
        ->whereNumber('id')
        ->name('categories.edit');
    Route::put('/categories/{id}', [CategoriesController::class, 'update'])
        ->whereNumber('id')
        ->name('categories.update');
    Route::delete('/categories/{id}', [CategoriesController::class, 'destroy'])
        ->whereNumber('id')
        ->name('categories.destroy');
});