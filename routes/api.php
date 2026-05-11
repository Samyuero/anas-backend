<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ✅ Public product browsing
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::post('/products/{id}/reviews', [ProductController::class, 'storeReview']);
Route::get('/categories', [ProductController::class, 'categories']);
Route::get('/brands', [ProductController::class, 'brands']);

// ✅ Public contact form
Route::post('/contact/store', [HomeController::class, 'storeContactApi']);

// ✅ Public slides for homepage
Route::get('/slides', [AdminController::class, 'apiSlides']);

// ✅ CART API ROUTES (Database-backed, for React frontend)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cart', [CartController::class, 'indexApi']);
    Route::post('/cart/add', [CartController::class, 'addToCartApi']);
    Route::put('/cart/update-quantity/{id}', [CartController::class, 'updateQtyApi']);
    Route::delete('/cart/remove/{id}', [CartController::class, 'removeCartItemApi']);
    Route::delete('/cart/clear', [CartController::class, 'clearCartApi']);
});

// ✅ WISHLIST API ROUTES (Database-backed, for React frontend)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'indexApi']);
    Route::post('/wishlist/add', [WishlistController::class, 'addToWishlistApi']);
    Route::post('/wishlist/check', [WishlistController::class, 'checkApi']);
    Route::delete('/wishlist/remove/{id}', [WishlistController::class, 'removeFromWishlistApi']);
    Route::delete('/wishlist/clear', [WishlistController::class, 'clearWishlistApi']);
    Route::post('/wishlist/move-to-cart/{id}', [WishlistController::class, 'moveToCartApi']);
});

// Protected routes (need authentication)
Route::middleware('auth:sanctum')->group(function () { 
    
    // User
    Route::get('/user', [AuthController::class, 'me']);
    Route::put('/user/update', [AuthController::class, 'updateProfile']);
    Route::put('/user/change-password', [AuthController::class, 'changePassword']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::put('/orders/{id}/cancel', [OrderController::class, 'cancel']);

    // Admin API Routes
    Route::middleware('admin.api')->group(function () {
        
        // Dashboard stats
        Route::get('/admin/dashboard-stats', [AdminController::class, 'apiDashboardStats']);
        
        // Products management
        Route::get('/admin/products', [AdminController::class, 'apiProducts']);
        Route::post('/admin/products', [AdminController::class, 'apiStoreProduct']);
        Route::get('/admin/products/{id}', [AdminController::class, 'apiShowProduct']);
        Route::post('/admin/products/{id}/update', [AdminController::class, 'apiUpdateProduct']);
        Route::delete('/admin/products/{id}', [AdminController::class, 'apiDeleteProduct']);
        
        // BRANDS 
        Route::get('/admin/brands', [AdminController::class, 'apiBrands']);
        Route::post('/admin/brands', [AdminController::class, 'apiStoreBrand']);
        Route::get('/admin/brands/{id}', [AdminController::class, 'apiShowBrand']);
        Route::post('/admin/brands/{id}', [AdminController::class, 'apiUpdateBrand']);
        Route::delete('/admin/brands/{id}', [AdminController::class, 'apiDeleteBrand']);
        
        // CATEGORIES 
        Route::get('/admin/categories', [AdminController::class, 'apiCategories']);
        Route::post('/admin/categories', [AdminController::class, 'apiStoreCategory']);
        Route::get('/admin/categories/{id}', [AdminController::class, 'apiShowCategory']);
        Route::post('/admin/categories/{id}', [AdminController::class, 'apiUpdateCategory']);
        Route::delete('/admin/categories/{id}', [AdminController::class, 'apiDeleteCategory']);
        
        // ORDERS 
        Route::get('/admin/orders', [AdminController::class, 'apiOrders']);
        Route::get('/admin/orders/{id}', [AdminController::class, 'apiOrderDetails']);
        Route::put('/admin/orders/{id}/status', [AdminController::class, 'apiUpdateOrderStatus']);
        
        // Low stock
        Route::get('/admin/low-stock', [AdminController::class, 'apiLowStock']);
        
        // Reports
        Route::get('/admin/report', [AdminController::class, 'apiReport']);
        
        // Slides
        Route::get('/admin/slides', [AdminController::class, 'apiSlides']);
        Route::post('/admin/slides', [AdminController::class, 'apiStoreSlide']);
        Route::get('/admin/slides/{id}', [AdminController::class, 'apiShowSlide']);
        Route::post('/admin/slides/{id}', [AdminController::class, 'apiUpdateSlide']);
        Route::delete('/admin/slides/{id}', [AdminController::class, 'apiDeleteSlide']);
        
        // Admin Accounts
        Route::get('/admin/accounts', [AdminController::class, 'apiAccounts']);
        Route::post('/admin/accounts', [AdminController::class, 'apiStoreAccount']);
        Route::delete('/admin/accounts/{id}', [AdminController::class, 'apiDeleteAccount']);
        
    });
    
});