<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Models\Inventory;

use App\Exports\TestResultsExport;
use Maatwebsite\Excel\Facades\Excel;

/*
|--------------------------------------------------------------------------
| PUBLIC (KHÔNG LOGIN)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

/*
|--------------------------------------------------------------------------
| AUTH (ĐÃ LOGIN)
|--------------------------------------------------------------------------
*/

Route::get('/san-pham', [ProductController::class, 'userProducts']);


// Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | USER
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth','role:user'])->group(function () {      
        // Cart
        Route::get('/cart', [CartController::class, 'index']);
        Route::get('/cart/mini', [CartController::class, 'miniCart']);
        Route::post('/cart/add', [CartController::class, 'add']);
        Route::post('/cart/update', [CartController::class, 'updateQty']);
        Route::post('/cart/remove', [CartController::class, 'removeAjax']);
        Route::post('/cart/update-qty', [CartController::class, 'updateQty']);
        Route::post('/cart/remove-mini', [CartController::class, 'removeMini']);
        // Checkout
        Route::get('/checkout', [CartController::class, 'checkoutPage']);
        Route::post('/checkout', [OrderController::class, 'checkout']);

        // Orders
        Route::get('/orders/my', [OrderController::class, 'myOrders']);
        Route::post('/orders/mark-paid/{id}', [OrderController::class, 'markPaid'])->middleware('auth');
        Route::post('/orders/received/{id}', [OrderController::class, 'markReceived'])->middleware('auth');
        Route::get('/orders/history', [OrderController::class, 'history']);

        Route::post('/returns/store', [OrderController::class, 'storeReturn']);

        // Payment
        Route::get('/payment/bank/{id}', [OrderController::class, 'bankPayment']);
        Route::get('/payment/qr/{id}', [OrderController::class, 'qrPayment']);
        Route::get('/payment/success/{id}', [OrderController::class, 'paymentSuccess']);

        Route::post('/review/store', [ReviewController::class, 'store']);
    });
    Route::post('/apply-coupon', [OrderController::class, 'applyCoupon']);
    /*
    |--------------------------------------------------------------------------
    | STAFF
    |--------------------------------------------------------------------------
    */
    Route::prefix('staff')->middleware('role:staff')->group(function () {

        Route::get('/dashboard', function () {
            return view('staff.dashboard');
        });

        // Orders
        Route::get('/orders', [OrderController::class, 'index']);
        Route::post('/orders/status/{id}', [OrderController::class, 'updateStatus']);
        Route::get('/orders/invoice/{id}', [OrderController::class, 'invoice']);
        Route::post('/orders/confirm/{id}', [OrderController::class, 'confirmOrder']);

        // Returns
        Route::get('/returns', [OrderController::class, 'returns']);
        Route::post('/returns/process/{id}', [OrderController::class, 'processReturn']);

        Route::get('/exchanges', [OrderController::class, 'exchanges']);
        Route::post('/exchanges/process/{id}', [OrderController::class,'processReturn']);

        // Inventory
        Route::get('/inventory', [InventoryController::class, 'index']);

        // Promotion
        Route::get('/promotion', [OrderController::class, 'promotion']);
        Route::post('/promotion/apply', [OrderController::class, 'applyPromotion']);
        Route::post('/apply-coupon', [OrderController::class, 'applyCoupon']);
        
        Route::get('/orders/confirm-payment/{id}', [OrderController::class, 'confirmPayment']);
        
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->middleware('role:admin')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index']);

        // PRODUCTS
        Route::get('/products', [ProductController::class, 'index']);
        Route::post('/products/store', [ProductController::class, 'store']);
        Route::post('/products/update/{id}', [ProductController::class, 'update']);
        Route::delete('/products/delete/{id}', [ProductController::class, 'delete']);

        // IMPORTS (NHẬP HÀNG)
        Route::get('/imports', [ImportController::class, 'index']);
        Route::post('/imports/store', [ImportController::class, 'store']);
        Route::get('/imports/delete/{id}', [ImportController::class, 'destroy']);

        // REPORT
        Route::get('/revenue', [ProductController::class, 'revenueReport']);
        Route::get('/best-selling', [ProductController::class, 'bestSellingProducts']);

        // Coupon
        Route::get('/coupons', [CouponController::class, 'index']);
        Route::post('/coupons/store', [CouponController::class, 'store']);
        Route::post('/coupons/update/{id}', [CouponController::class, 'update']);
        Route::post('/coupons/delete/{id}', [CouponController::class, 'delete']);

        // Users
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users/store', [UserController::class, 'store']);
        Route::post('/users/toggle/{id}', [UserController::class, 'toggle']);
        Route::post('/users/reset/{id}', [UserController::class, 'resetPassword']);

        Route::get('/revenue/export',[OrderController::class, 'exportRevenue']);
        Route::get('/best-selling/export',[OrderController::class, 'exportBestSelling']);

    });
    Route::get('/export-test-results', function () {
        return Excel::download(new TestResultsExport,'test_results.xlsx');
    });

    Route::get('/test-db', function () {
        return DB::connection('mysql_testing')
            ->table('test_results')
            ->get();
    });