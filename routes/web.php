<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Guest\CatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AddressController;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\StripeController;

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::patch('admin/users/{user?}/toggle-status', [UserController::class, 'toggleStatus'])
    ->name('users.toggle-status');

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('categories', CategoryController::class);

        Route::resource('products', ProductController::class)
            ->except(['show', 'edit', 'update']);

        Route::get('users', [UserController::class, 'index'])
            ->name('users.index');

        Route::get('products/create', [ProductController::class, 'create'])
            ->name('products.create');

        Route::post('products/store', [ProductController::class, 'store'])
            ->name('admin.products.store');

        Route::get('orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])
            ->name('orders.index');

        Route::get(
            'categories/{category}/delete',
            [CategoryController::class, 'destroy']
        )->name('categories.delete');
    });

/*
|--------------------------------------------------------------------------
| USER ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:user'])
    ->prefix('user')
    ->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])
            ->name('user.dashboard');
    });

/*
|--------------------------------------------------------------------------
| AUTHENTICATED SHOP FLOW (CART → SUMMARY → CHECKOUT)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // AJAX CART ACTIONS (NEW)
    Route::post('/cart/action', [CartController::class, 'handleAjax'])
        ->name('cart.action');

    // CART
    Route::post('/cart/add', [CartController::class, 'add'])
        ->name('cart.add');

    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])
        ->name('cart.remove');

    Route::get('/cart', [CartController::class, 'index'])
        ->name('cart.view');

    Route::delete('/cart', [CartController::class, 'clear'])
        ->name('cart.clear');

    // ORDER SUMMARY
    Route::get('/order-summary', [OrderController::class, 'summary'])
        ->name('order.summary');

    // ADDRESS
    Route::post('/address/store', [AddressController::class, 'store'])
        ->name('address.store');

    // Force select address for checkout
    Route::post('/address/select', [AddressController::class, 'select'])
        ->name('address.select');

    // CHECKOUT
    Route::get('/checkout', [CheckoutController::class, 'index'])
        ->name('checkout.index');

    Route::post('/checkout', [CheckoutController::class, 'store'])
        ->name('checkout.store');

    Route::post('/checkout/process', function () {
        return view('order.success');
    })->name('checkout.process');
});
/*
|--------------------------------------------------------------------------
| GUEST ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [CatalogController::class, 'index'])
    ->name('guest.products.index');

/*
|--------------------------------------------------------------------------
| JETSTREAM DASHBOARD
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
//order
// The validation is there to prevent users from viewing someone else’s order by URL tampering.
Route::get('/order/success/{order}', function (Order $order) {
    abort_if($order->user_id !== auth::id(), 403);
    return view('order.success', compact('order'));
})->middleware('auth')->name('order.success');

Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])
    ->name('checkout.placeOrder')
    ->middleware('auth');

Route::get('/orders/success/{order}', [OrderController::class, 'success'])
    ->name('orders.success')
    ->middleware('auth');


Route::get('/stripe/checkout/{order}', [StripeController::class, 'checkout'])
    ->name('stripe.checkout');

Route::get('/stripe/success/{order}', [StripeController::class, 'success'])
    ->name('stripe.success');

Route::get('/stripe/cancel/{order}', [StripeController::class, 'cancel'])
    ->name('stripe.cancel');
