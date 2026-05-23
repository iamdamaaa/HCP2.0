<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;

//PUBLIC - Perlu login
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login',    [AuthController::class, 'login']);

// PUBLIC — tidak perlu login

// OTP — pintu masuk utama sistem
Route::prefix('otp')->group(function () {
    Route::post('/send',   [AuthController::class, 'sendOtp']);
    Route::post('/verify', [AuthController::class, 'verifyOtp']);
});

// Pelanggan bisa lihat layanan & kategori tanpa login
// Pakai AdminServiceController karena index() sudah ada
Route::get('/services',   [AdminServiceController::class,  'index']);
Route::get('/categories', [AdminCategoryController::class, 'index']);

// AUTHENTICATED — semua role yang sudah login
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout',  [AuthController::class, 'logout']);
    Route::get('/profile',  [AuthController::class, 'profile']);

    // ── USER (Pelanggan) ──────────────────────────────────
    // role:user sesuai enum ['user','mitra','employee','admin']
    Route::middleware('role:user')->prefix('orders')->group(function () {
        Route::get('/',              [AuthController::class, 'index']);   // sementara
        Route::post('/',             [AuthController::class, 'index']);   // sementara
        Route::get('/{id}',          [AuthController::class, 'index']);   // sementara
        Route::patch('/{id}/cancel', [AuthController::class, 'index']);   // sementara
    });

    // ── EMPLOYEE (Karyawan) ───────────────────────────────
    Route::middleware('role:employee')->prefix('employee/orders')->group(function () {
        Route::get('/',              [AuthController::class, 'index']);   // sementara
        Route::get('/{id}',          [AuthController::class, 'index']);   // sementara
        Route::patch('/{id}/status', [AuthController::class, 'index']);   // sementara
    });

    // ── ADMIN ─────────────────────────────────────────────
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::apiResource('categories', AdminCategoryController::class);
        Route::apiResource('services',   AdminServiceController::class);

        // Uncomment setelah Controller dibuat:
        // Route::apiResource('orders', AdminOrderController::class);
        // Route::post('orders/{id}/assign', [AdminOrderController::class, 'assign']);
        // Route::get('users', [AdminUserController::class, 'index']);
        // Route::patch('users/{id}/role', [AdminUserController::class, 'updateRole']);
    });
});