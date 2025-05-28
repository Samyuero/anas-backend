<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthAdmin;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;


Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product_slug}', [ShopController::class, 'productDetails'])->name('shop.product.details');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::put('/cart/increase/{rowId}', [CartController::class, 'increaseCartItem'])->name('cart.qty.increase');
Route::put('/cart/decrease/{rowId}', [CartController::class, 'decreaseCartItem'])->name('cart.qty.decrease');
Route::delete('/cart/remove/{rowId}', [CartController::class, 'removeCartItem'])->name('cart.item.remove');
Route::delete('/cart/clear', [CartController::class, 'clearCart'])->name('cart.item.clear');
Route::put('/cart/{rowId}/update', [CartController::class, 'updateQty'])->name('cart.qty.update');
Route::put('/cart/update-quantity/{rowId}', [CartController::class, 'updateQty'])->name('cart.qty.update');

Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/add', [WishlistController::class, 'addToWishlist'])->name('wishlist.add');
Route::delete('/wishlist/remove/{rowId}', [WishlistController::class, 'removeFromWishlist'])->name('wishlist.item.remove');
Route::delete('/wishlist/clear', [WishlistController::class, 'clearWishlist'])->name('wishlist.item.clear');
Route::post('/wishlist/move-to-cart/{rowId}', [WishlistController::class, 'moveToCart'])->name('wishlist.move.to.cart');


Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::post('/place-order', [CartController::class, 'placeOrder'])->name('cart.place.order');
Route::get('/order-confirmation', [CartController::class, 'orderConfirmation'])->name('cart.order.confirmation');


Route::get('contact-us', [HomeController::class, 'contact'])->name('home.contact');
Route::post('/contact/store', [HomeController::class, 'storeContact'])->name('home.contact.store');

Route::get('/search', [HomeController::class, 'searchProduct'])->name('home.search');

Route::middleware(['auth'])->group(function () {
    Route::get('/account-dashboard', [UserController::class, 'index'])->name('user.index');
    Route::get('/account-orders', [UserController::class, 'orders'])->name('user.orders');
    Route::get('/account-orders/{order_id}/details', [UserController::class, 'orderDetails'])->name('user.order.details');
    Route::put('/account-order/cancel-order', [UserController::class, 'orderCancel'])->name('user.order.cancel');
    Route::get('/account-details', [UserController::class, 'accountDetails'])->name('user.account.details');
    Route::put('/account-details/update', [UserController::class, 'update'])->name('user.account.update');
    Route::get('/account/change-password', [UserController::class, 'showChangePasswordForm'])->name('user.change.password');
    Route::post('/account/change-password', [UserController::class, 'changePassword'])->name('user.password.update');
    Route::get('/edit-address', [CartController::class, 'editAddress'])->name('cart.edit.address');
    Route::put('/update-address', [CartController::class, 'updateAddress'])->name('cart.update.address');
});

Route::middleware(['auth', AuthAdmin::class])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/brands', [AdminController::class, 'brands'])->name('admin.brands');
    Route::get('/admin/brands/create', [AdminController::class, 'createBrand'])->name('admin.brands.create');
    Route::post('/admin/brands/store', [AdminController::class, 'storeBrand'])->name('admin.brands.store');
    Route::get('/admin/brands/{id}/edit', [AdminController::class, 'editBrand'])->name('admin.brands.edit');
    Route::put('/admin/brands/update', [AdminController::class, 'updateBrand'])->name('admin.brands.update');
    Route::delete('/admin/brands/{id}/delete', [AdminController::class, 'deleteBrand'])->name('admin.brands.delete');

    Route::get('/admin/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::get('/admin/categories/create', [AdminController::class, 'createCategory'])->name('admin.categories.create');
    Route::post('/admin/categories/store', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
    Route::get('/admin/categories/{id}/edit', [AdminController::class, 'editCategory'])->name('admin.categories.edit');
    Route::put('/admin/categories/update', [AdminController::class, 'updateCategory'])->name('admin.categories.update');
    Route::delete('/admin/categories/{id}/delete', [AdminController::class, 'deleteCategory'])->name('admin.categories.delete');

    Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/admin/products/create', [AdminController::class, 'createProduct'])->name('admin.products.create');
    Route::post('/admin/products/store', [AdminController::class, 'storeProduct'])->name('admin.products.store');
    Route::get('/admin/products/{id}/edit', [AdminController::class, 'editProduct'])->name('admin.products.edit');
    Route::put('/admin/products/update', [AdminController::class, 'updateProduct'])->name('admin.products.update');
    Route::delete('/admin/products/{id}/delete', [AdminController::class, 'deleteProduct'])->name('admin.products.delete');

    Route::get('/admin/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::get('/admin/order/{order_id}/details', [AdminController::class, 'orderDetails'])->name('admin.order.details');
    Route::put('/admin/orders/update-status', [AdminController::class, 'updateOrderStatus'])->name('admin.order.status.update');

    Route::get('/admin/slides', [AdminController::class, 'slides'])->name('admin.slides');
    Route::get('/admin/slides/add', [AdminController::class, 'addSlides'])->name('admin.add.slides');
    Route::post('/admin/slide/store', [AdminController::class, 'storeSlides'])->name('admin.add.store');
    Route::get('/admin/slide/{id}/edit', [AdminController::class, 'editSlide'])->name('admin.edit.slides');
    Route::put('/admin/slide/update', [AdminController::class, 'updateSlide'])->name('admin.update.slides');
    Route::delete('/admin/slide/{id}/delete', [AdminController::class, 'deleteSlides'])->name('admin.delete.slides');

    Route::get('/admin/contacts', [AdminController::class, 'contacts'])->name('admin.contacts');
    Route::delete('admin/contact/{id}/delete', [AdminController::class, 'deleteContact'])->name('admin.contact.delete');

    Route::get('/admin/search', [Admincontroller::class, 'search'])->name('admin.search');
});
