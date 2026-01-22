<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Guest\CatalogController;
use App\Http\Controllers\CartController;

Route::patch('admin/users/{user?}/toggle-status', [UserController::class, 'toggleStatus'])
    ->name('users.toggle-status');

Route::get('admin/users', [UserController::class, 'index'])
    ->name('admin.users.index');

// Route::middleware(['auth', 'role:admin'])
//     ->prefix('admin')
//     ->name('admin.')
//     ->group(function () {

//     });


Route::get(
    'categories/{category}/delete',
    [CategoryController::class, 'destroy']
)
    ->name('admin.categories.delete');


Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class)->except(['show', 'edit', 'update']);
    });


Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('admin.dashboard');
    });

Route::middleware(['auth', 'role:user'])
    ->prefix('user')
    ->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])
            ->name('user.dashboard');
    });

Route::middleware('auth')->group(function () {
    Route::post('/cart/add', [CartController::class, 'add'])
        ->name('cart.add');
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])
        ->name('cart.remove');
    Route::get('/cart', [CartController::class, 'index'])
        ->name('cart.view');
});

// Route::get('/', function () {
//     return view('welcome');
// });



Route::get('/', [CatalogController::class, 'index'])
    ->name('guest.products.index');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
