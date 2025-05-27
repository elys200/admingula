<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\ResepController;
use App\Http\Controllers\KategorigulaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JurnalController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', function () {
    return response ([
        'message' => 'Api is working'
    ], 200,);
});

// Authentication Mobile User
Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login']);

// Authentication Admin
Route::prefix('admin')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login']);
});

//Berita CRUD
Route::prefix('berita')->group(function () {
    Route::get('/', [BeritaController::class, 'index']);
    Route::get('/{id}', [BeritaController::class, 'show']);
    Route::post('/', [BeritaController::class, 'store']);
    Route::put('/{id}', [BeritaController::class, 'update']);
    Route::delete('/{id}', [BeritaController::class, 'destroy']);
});

//Resep CRUD
Route::prefix('resep')->group(function () {
    Route::get('/', [ResepController::class, 'index']);
    Route::get('/{id}', [ResepController::class, 'show']);
    Route::post('/', [ResepController::class, 'store']);
    Route::put('/{id}', [ResepController::class, 'update']);
    Route::delete('/{id}', [ResepController::class, 'destroy']);
});

//Kategori Gula CRUD
Route::prefix('kategori_gula')->group(function () {
    Route::get('/', [KategorigulaController::class, 'index']);
    Route::get('/{id}', [KategorigulaController::class, 'show']);
    Route::post('/', [KategorigulaController::class, 'store']);
    Route::put('/{id}', [KategorigulaController::class, 'update']);
    Route::delete('/{id}', [KategorigulaController::class, 'destroy']);
});