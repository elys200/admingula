<?php

use App\Http\Controllers\AdminJurnalController;
use App\Http\Controllers\user\UserJurnalController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\AuthenticationController;
use App\Http\Controllers\user\UserProfileController;
use App\Http\Controllers\user\ResepFavoritController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\ResepController;
use App\Http\Controllers\KategorigulaController;

Route::prefix('v1')->group(function () {

    Route::get('/test', fn () => response(['message' => 'API is working'], 200));

    // User Authentication
    Route::post('/register', [AuthenticationController::class, 'register']);
    Route::post('/login', [AuthenticationController::class, 'login']);

    // Admin Authentication
    Route::prefix('admin')->group(function () {
        Route::post('/login', [AdminAuthController::class, 'login']);
        Route::post('/register', [AdminAuthController::class, 'register']);
    });

    // Admin jurnal
    Route::get('/jurnal', [AdminJurnalController::class, 'index']);

    // Protected User Routes
    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/logout', [AuthenticationController::class, 'logout']);

        Route::prefix('user/profile')->group(function () {
            Route::get('/', [UserProfileController::class, 'show']);
            Route::put('/', [UserProfileController::class, 'update']);
            Route::post('/foto', [UserProfileController::class, 'updatePhoto']);
            Route::delete('/foto', [UserProfileController::class, 'deletePhoto']);
            Route::post('/change-password', [UserProfileController::class, 'changePassword']);
        });

        Route::prefix('resep-favorit')->group(function () {
            Route::get('/', [ResepFavoritController::class, 'show']);
            Route::post('/toggle', [ResepFavoritController::class, 'toggle']);
        });

        // 📄 User: hanya miliknya sendiri
        Route::prefix('jurnal/user')->group(function () {
            Route::get('/', [UserJurnalController::class, 'index']);
            Route::post('/', [UserJurnalController::class, 'store']);
        });
    });

    // Berita CRUD
    Route::prefix('berita')->group(function () {
        Route::get('/', [BeritaController::class, 'index']);
        Route::get('/{id}', [BeritaController::class, 'show']);
        Route::post('/', [BeritaController::class, 'store']);
        Route::put('/{id}', [BeritaController::class, 'update']);
        Route::delete('/{id}', [BeritaController::class, 'destroy']);
        Route::get('/kategori/{kategori}', [BeritaController::class, 'kategori']);
    });

    Route::get('/berita-all', [BeritaController::class, 'getAll']);
    Route::get('/resep-terbaru', [ResepController::class, 'getTop3']);
    Route::get('/resep-all', [ResepController::class, 'getAll']);

    // Resep CRUD
    Route::prefix('resep')->group(function () {
        Route::get('/', [ResepController::class, 'index']);
        Route::get('/{id}', [ResepController::class, 'show']);
        Route::post('/', [ResepController::class, 'store']);
        Route::put('/{id}', [ResepController::class, 'update']);
        Route::delete('/{id}', [ResepController::class, 'destroy']);
    });

    // Kategori Gula CRUD
    Route::prefix('kategori_gula')->group(function () {
        Route::get('/', [KategorigulaController::class, 'index']);
        Route::get('/{id}', [KategorigulaController::class, 'show']);
        Route::post('/', [KategorigulaController::class, 'store']);
        Route::put('/{id}', [KategorigulaController::class, 'update']);
        Route::delete('/{id}', [KategorigulaController::class, 'destroy']);
    });
});
