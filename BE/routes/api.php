<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Employee\OrderController as EmployeeOrderController;
// Note: Controller Admin untuk Order, Kategori, User perlu dibuat nanti (karena belum ada di task sebelumnya).
// Untuk rute admin yang controllernya belum dibuat, saya arahkan sementara ke class dummy atau di-comment agar tidak error.
// Karena instruksinya adalah membuat ROUTES, saya akan membuat strukturnya lengkap sesuai permintaan.

// ── PUBLIC (tidak perlu login) ──────────────────
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::prefix('otp')->group(function () {
    Route::post('/send', [AuthController::class, 'sendOtp']);
    Route::post('/verify', [AuthController::class, 'verifyOtp']);
});

// Pelanggan bisa lihat layanan tanpa login
// Menggunakan ServiceController dari Admin sementara untuk endpoint public (index saja)
Route::get('/services', [AdminServiceController::class, 'index']);

// ── AUTHENTICATED (semua role yang sudah login) ──
Route::middleware('auth:sanctum')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::get('/profile', function (Request $request) {
        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diambil',
            'data' => $request->user()
        ]);
    });

    // ── PELANGGAN ────────────────────────────────────
    Route::middleware('role:customer')->group(function () {
        Route::prefix('orders')->group(function () {
            Route::get('/', [CustomerOrderController::class, 'index']);
            Route::post('/', [CustomerOrderController::class, 'store']);
            Route::get('/{id}', [CustomerOrderController::class, 'show']);
            Route::patch('/{id}/cancel', [CustomerOrderController::class, 'cancel']);
        });
    });

    // ── KARYAWAN ─────────────────────────────────────
    Route::middleware('role:employee')->prefix('employee')->group(function () {
        Route::prefix('orders')->group(function () {
            Route::get('/', [EmployeeOrderController::class, 'index']);
            Route::get('/{id}', [EmployeeOrderController::class, 'show']);
            Route::patch('/{id}/status', [EmployeeOrderController::class, 'updateStatus']);
        });
    });

    // ── ADMIN ────────────────────────────────────────
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        
        // --- Layanan ---
        Route::apiResource('services', AdminServiceController::class);
        
        // --- Orders (Contoh struktur, controller belum dibuat di iterasi sebelumnya) ---
        // Route::apiResource('orders', App\Http\Controllers\Admin\OrderController::class);
        // Route::post('orders/{id}/assign', [App\Http\Controllers\Admin\OrderController::class, 'assign']);

        // --- Kategori (Contoh struktur, controller belum dibuat di iterasi sebelumnya) ---
        // Route::apiResource('categories', App\Http\Controllers\Admin\CategoryController::class);

        // --- Users (Contoh struktur, controller belum dibuat di iterasi sebelumnya) ---
        // Route::get('users', [App\Http\Controllers\Admin\UserController::class, 'index']);
        // Route::patch('users/{id}/role', [App\Http\Controllers\Admin\UserController::class, 'updateRole']);
    });
});
