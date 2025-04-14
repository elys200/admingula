<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;


Route::get('/login', [LoginController::class, 'index']);
Route::get('/', function () {
    return view('welcome');
});
