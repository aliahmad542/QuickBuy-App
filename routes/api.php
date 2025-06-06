<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfilePhotoController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserLocationController;
use App\Http\Controllers\FavoritesController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// User Management
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'getUserInfo']);
    Route::post('/user/location' , [UserLocationController::class , 'updateLocation']);
    Route::post('/user/password', [UserController::class, 'updatePassword']);
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::post('/profile/photo', [ProfilePhotoController::class, 'updateProfilePhoto']);
});

// Store Management
Route::post('create-store', [StoreController::class, 'createStore']);
Route::delete('/delete-store/{id}', [StoreController::class, 'deleteStore']);
Route::post('add-products', [ProductController::class, 'addProduct']);
Route::get('get-store-with-products', [StoreController::class, 'getStoreWithProducts']);

// Cart Operations
Route::middleware('auth:sanctum')->group(function () {
    Route::post('cart-add/{productId}/{quantity}', [CartController::class, 'addToCart']);

    //newwwwwwwwwwwww edit
    Route::post('update-order-quantity/{orderId}',[CartController::class,'update_order_quantity']);
Route::get('delete-order/{orderId}',[ProductController::class,'deleteOrder']);
    Route::post('buy-from-cart/{cartItemId}/{productId}/{quantity}', [CartController::class, 'buyProductFromCart']);
    Route::post('buy-all-from-cart', [CartController::class, 'buy_all_in_cart']);
    Route::post('admin-reject',[CartController::class,'rejectAllOrders']);

    Route::post('admin-accept-all',[CartController::class,'acceptAllOrders']);

    Route::delete('cart-remove-item/{cartItemId}', [CartController::class, 'removeFromCart']);
    Route::get('cart', [CartController::class, 'showCart']);
    Route::get('orders', [CartController::class, 'getUserOrders']);
    Route::post('admin-accept/{order_id}',[CartController::class,'accept_request']);
    Route::post('admin-reject/{order_id}',[CartController::class,'reject_request']);
    Route::post('update-order/{cart_item}/{new_quantity}',[ProductController::class,'update_order'])->name('product.order');

});



// favorites cart :)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/favorites/{productId}', [FavoritesController::class, 'addToFavorites']);
    Route::get('/favorites', [FavoritesController::class, 'showFavorites']);
    Route::delete('/favorites/{productId}', [FavoritesController::class, 'removeFromFavorites']);
});
