<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRegisterRequest;
use App\Http\Requests\AdminLoginRequest;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    //Fungsi untuk login admin
    public function login(Request $request)
{
    $credentials = $request->only('username', 'password');

    if (Auth::guard('admin')->attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->route('dashboard');
    }

    return back()->withErrors([
        'username' => 'Username atau password salah.',
    ]);
}
    //Fungsi untuk logout admin
    public function logout(Request $request)
{
    Auth::guard('admin')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
}

}