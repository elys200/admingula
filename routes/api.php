<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\AuthenticationController;
use App\Http\Controllers\user\UserProfileController;
use App\Http\Controllers\user\ResepFavoritController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\ResepController;
use App\Http\Controllers\KategorigulaController;
use App\Http\Controllers\JurnalController;

Route::prefix('v1')->group(function () {
    
    // Public Routes
    Route::get('/test', function () {
        return response([
            'message' => 'API is working'
        ], 200);
    });

    // User Authentication (Public)
    Route::post('/register', [AuthenticationController::class, 'register']);
    Route::post('/login', [AuthenticationController::class, 'login']);

    // Admin Authentication (Public)
    Route::prefix('admin')->group(function () {
        Route::post('/login', [AdminAuthController::class, 'login']);
        Route::post('/register', [AdminAuthController::class, 'register']);
    });

    // Protected Routes (Authenticated Users Only)
    Route::middleware('auth:sanctum')->group(function () {
        
        // User Logout
        Route::post('/logout', [AuthenticationController::class, 'logout']);

        // User Profile
        Route::prefix('user/profile')->group(function () {
            Route::get('/', [UserProfileController::class, 'show']);
            Route::put('/', [UserProfileController::class, 'update']);
            Route::post('/foto', [UserProfileController::class, 'updatePhoto']);
            Route::post('/change-password', [UserProfileController::class, 'changePassword']);
        });

        // Resep Favorit
        Route::prefix('resep-favorit')->group(function () {
            Route::get('/', [ResepFavoritController::class, 'show']);
            Route::post('/toggle', [ResepFavoritController::class, 'toggle']);
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

    // Berita untuk mobile
    Route::get('/berita-all', [BeritaController::class, 'getAll']);
    //3 Resep Terbaru
    Route::get('/resep-terbaru', [ResepController::class, 'getTop3']);
    // Resep untuk mobile
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
