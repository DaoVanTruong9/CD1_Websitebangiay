<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| KHÔNG CẦN LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

// Route::get('/', [ProductController::class, 'shop']);

/*
|--------------------------------------------------------------------------
| CẦN LOGIN
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // ================= DASHBOARD =================

    // ADMIN
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('role:admin');

    // STAFF
    Route::get('/staff/dashboard', function () {
        return view('staff.dashboard');
    })->middleware('role:staff');

    // USER (có thể bỏ vì user về trang / rồi)
    Route::get('/user/home', function () {
        return redirect('/');
    })->middleware('role:user');

    // ================= LOGOUT =================
    Route::post('/logout', [AuthController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | USER
    |--------------------------------------------------------------------------
    */
    Route::post('/checkout', [OrderController::class, 'checkout'])
        ->middleware('role:user');

    /*
    |--------------------------------------------------------------------------
    | STAFF + ADMIN
    |--------------------------------------------------------------------------
    */
    Route::get('/orders', [OrderController::class, 'index'])
        ->middleware('role:staff,admin');

    Route::get('/orders/status/{id}/{status}', [OrderController::class, 'updateStatus'])
        ->middleware('role:staff,admin');

    Route::get('/orders/invoice/{id}', [OrderController::class, 'invoice'])
        ->middleware('role:staff,admin');

    /*
    |--------------------------------------------------------------------------
    | STAFF (TRANG MỚI)
    |--------------------------------------------------------------------------
    */
    Route::get('/returns', function () {
        return view('staff.returns'); // tạo file sau
    })->middleware('role:staff');

    Route::get('/inventory', function () {
        return view('staff.inventory');
    })->middleware('role:staff');

    Route::get('/promotion', function () {
        return view('staff.promotion');
    })->middleware('role:staff');

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */
    Route::get('/products', [ProductController::class, 'index'])
        ->middleware('role:admin');

    Route::post('/products/store', [ProductController::class, 'store'])
        ->middleware('role:admin');

    Route::get('/products/delete/{id}', [ProductController::class, 'delete'])
        ->middleware('role:admin');
    Route::post('/products/update/{id}', [ProductController::class,'update']);    
        
    Route::get('/reports', [OrderController::class, 'report'])
        ->middleware('role:admin');

    Route::middleware(['auth','role:staff'])->group(function () {

    Route::get('/staff/dashboard', function () {
        return view('staff.dashboard');
    });

    // 📦 Đơn hàng
    Route::get('/staff/orders', [OrderController::class, 'index']);
    Route::get('/staff/orders/status/{id}/{status}', [OrderController::class, 'updateStatus']);

    // 🔄 Trả hàng
    Route::get('/staff/returns', [OrderController::class, 'returns']);
    Route::post('/staff/returns/process/{id}', [OrderController::class, 'processReturn']);

    // 📦 Tồn kho
    Route::get('/staff/inventory', [ProductController::class, 'inventory']);

    // 🏷️ Khuyến mãi
    Route::get('/staff/promotion', [ProductController::class, 'promotion']);
    Route::post('/staff/promotion/apply/{id}', [ProductController::class, 'applyPromotion']);

    });

});