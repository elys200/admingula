<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JurnalController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\ResepController;
use App\Http\Controllers\KategorigulaController;
use App\Http\Controllers\ProfileController;


Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::get('/kategori_gula', [KategorigulaController::class, 'index'])->name('kategorigula');
Route::get('/resep', [ResepController::class, 'index'])->name('resep');
Route::get('/berita', [BeritaController::class, 'index'])->name('berita');
Route::get('/jurnal', [JurnalController::class, 'index'])->name('jurnal');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/', function () {
    return view('welcome');
});
