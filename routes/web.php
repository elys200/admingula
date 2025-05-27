<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JurnalController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\ResepController;
use App\Http\Controllers\KategorigulaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AdminAuthController;

Route::get('/', function () {
    return view('welcome'); // halaman login
});

//Login
Route::post('/login', [AdminAuthController::class, 'login'])->name('login');

//Logout
Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

//Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth:admin')->name('dashboard');

//Berita
Route::get('/berita', [BeritaController::class, 'view'])->middleware('auth:admin')->name('berita');

//Resep Makanan
Route::get('/resep', [ResepController::class, 'view'])->middleware('auth:admin')->name('resep_makanan');

//Kategori Gula
Route::get('/kategori_gula', [KategorigulaController::class, 'view'])->middleware('auth:admin')->name('kategori_gula');

//Profile
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

//Jurnal
Route::get('/jurnal', [JurnalController::class, 'index'])->name('jurnal');
