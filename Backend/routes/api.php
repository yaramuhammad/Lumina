<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\AddressController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->get('/user', function (Request $request) {
    return $request->user();
});

require __DIR__.'/auth.php';

Route::get('/products', [ProductController::class, 'index']);

Route::get('/products/{product}', [ProductController::class, 'show']);

Route::get('/categories', [CategoryController::class, 'index']);

Route::get('/categories/{category}', [CategoryController::class, 'show']);

Route::get('/brands', [BrandController::class, 'index']);

Route::get('/brands/{brand}', [BrandController::class, 'show']);

Route::get('/categories/{category}/{subcategory}', [SubCategoryController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    //Cart
    Route::post('/cart/items', [CartController::class, 'addItem']);
    Route::get('/cart', [CartController::class, 'viewCart']);
    Route::patch('/cart/items/{itemId}', [CartController::class, 'updateItem']);
    Route::delete('/cart/items/{itemId}', [CartController::class, 'removeItem']);
    Route::delete('/cart', [CartController::class, 'clearCart']);
    //Wishlist
    Route::post('/wishlist',[WishlistController::class,'AddProductToWishlist']);
    Route::get('/wishlist',[WishlistController::class,'getWishlist']);
    Route::delete('/wishlist',[WishlistController::class,'removeFromWishlist']);
    //Addresses
    Route::post('/addresses', [AddressController::class, 'addAddress']);
    Route::get('/addresses', [AddressController::class, 'getAddresses']);
    Route::delete('/addresses/{id}', [AddressController::class, 'removeAddress']);
});
