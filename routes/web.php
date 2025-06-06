<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard',[\App\Http\Controllers\StoreController::class, 'get_Store_With_Products'])->name('get_stores')->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('get-store-with-products', [\App\Http\Controllers\StoreController::class, 'get_Store_With_Products'])->name('get_stores');
Route::post('create-store', [\App\Http\Controllers\StoreController::class, 'create_Store'])->name('store.create');
Route::get('store.create',[\App\Http\Controllers\StoreController::class,'store'])->name('create.store');
Route::get('delete-store/{id}', [\App\Http\Controllers\StoreController::class, 'delete_Store'])->name('store.delete');
Route::post('add-products', [\App\Http\Controllers\ProductController::class, 'add_Product'])->name('product.store');
Route::get('show-product',[\App\Http\Controllers\ProductController::class,'get_product_insert'])->name('product');
Route::get('product-show',[\App\Http\Controllers\ProductController::class,'show'])->name('product.show');
Route::post('admin-accept/{order_id}',[\App\Http\Controllers\CartController::class,'accept_request'])->name('accept');
Route::post('admin-reject/{order_id}',[\App\Http\Controllers\CartController::class,'reject_request'])->name('refuse');
Route::get('show-store-create',[\App\Http\Controllers\StoreController::class,'show_store_create'])->name('stores.show');
Route::get('show-product-store',[\App\Http\Controllers\ProductController::class,'show_product_store'])->name('show.product.store');
Route::get('product-product/{idp}/{idst}',[\App\Http\Controllers\ProductController::class,'delete_product'])->name('product.delete');
Route::get('get-products-from-show/{storeId}',[\App\Http\Controllers\ProductController::class,'get_products_from_controllers'])->name('store.details');
Route::get('get-orders',[\App\Http\Controllers\ProductController::class,'get_orders'])->name('orders.get');
Route::post('admin-auth', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store_admin'])->name('auth.admin');
Route::get('get-products-from-show/{storeId}',[\App\Http\Controllers\ProductController::class,'get_products_from_controllers'])->name('store.details');



require __DIR__.'/auth.php';
