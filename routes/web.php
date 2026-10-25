<?php

use Illuminate\Support\Facades\Route;

// Import Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\UserAddressController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ChatController;

// Import Admin Controllers & Middleware
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminSubCategoryController;
use App\Http\Controllers\AdminSubSubCategoryController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\MenuTabController;

/*
|--------------------------------------------------------------------------
| Autentikasi 
|--------------------------------------------------------------------------
*/

Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

/*
|--------------------------------------------------------------------------
| Halaman Publik & Katalog
|--------------------------------------------------------------------------
*/
Route::get('/', [ProductController::class, 'index']);
Route::get('/katalog', [ProductController::class, 'index']);
Route::get('/produk/{slug}', [ProductController::class, 'show']);

/*
|--------------------------------------------------------------------------
| Keranjang Belanja
|--------------------------------------------------------------------------
*/
Route::get('/keranjang', [CartController::class, 'index']);
Route::post('/keranjang/tambah', [CartController::class, 'add']);
Route::post('/keranjang/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::delete('/keranjang/{cartKey}', [CartController::class, 'destroy'])->name('cart.destroy');

/*
|--------------------------------------------------------------------------
| Area users
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profil', [UserProfileController::class, 'edit'])->name('user.profile.edit');
    Route::put('/profil', [UserProfileController::class, 'update'])->name('user.profile.update');
    Route::get('/profil/password', [UserProfileController::class, 'editPassword'])->name('user.password.edit');
    Route::put('/profil/password', [UserProfileController::class, 'updatePassword'])->name('user.password.update');
    Route::get('/profil/keamanan', [UserProfileController::class, 'security'])->name('user.security');
    Route::delete('/profil/keamanan', [UserProfileController::class, 'destroyAccount'])->name('user.account.destroy');
    Route::get('/profil/alamat', [UserAddressController::class, 'index'])->name('user.address.index');
    Route::post('/profil/alamat', [UserAddressController::class, 'store'])->name('user.address.store');
    Route::put('/profil/alamat/{address}', [UserAddressController::class, 'update'])->name('user.address.update');
    Route::delete('/profil/alamat/{address}', [UserAddressController::class, 'destroy'])->name('user.address.destroy');
    Route::put('/profil/alamat/{address}/utama', [UserAddressController::class, 'setPrimary'])->name('user.address.setPrimary');
    Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
    Route::post('/order/create', [CheckoutController::class, 'store'])->name('order.store');
    Route::get('/riwayat-pesanan', [CheckoutController::class, 'history'])->name('order.history');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/favorit', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorit/{product}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/toko/{id}', [StoreController::class, 'show'])->name('store.show');
    Route::post('/chat/mulai/{seller_id}', [ChatController::class, 'startChat'])->name('chat.start');
    Route::get('/pesan', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/pesan/{chat}', [ChatController::class, 'store'])->name('chat.store');
    Route::post('/upgrade-seller', [AuthController::class, 'upgradeToSeller'])->name('upgrade.seller');
});

/*
|--------------------------------------------------------------------------
| Area Sellers
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'seller'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', AdminProductController::class);
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::put('/orders/{id}', [AdminOrderController::class, 'update']);
    Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::put('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('admin.profile.update');
});

/*
|--------------------------------------------------------------------------
| Area Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', IsAdmin::class])->prefix('admin')->group(function () {
    Route::resource('menu-tabs', MenuTabController::class)->except(['create', 'show', 'edit']);
    Route::resource('categories', AdminCategoryController::class);
    Route::get('/subcategories/create', [AdminSubCategoryController::class, 'create']);
    Route::post('/subcategories', [AdminSubCategoryController::class, 'store']);
    Route::delete('/subcategories/{id}', [AdminSubCategoryController::class, 'destroy']);
    Route::resource('subsubcategories', AdminSubSubCategoryController::class)->except(['show', 'index', 'edit', 'update']);
});
