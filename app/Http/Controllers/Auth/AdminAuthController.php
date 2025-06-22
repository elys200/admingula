<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AdminLoginRequest;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.admin-login');
    }

    // Login Admin
    public function login(AdminLoginRequest $request)
    {
        $credentials = $request->only('username', 'password');
        $isApi = $request->expectsJson() || $request->isJson() || $request->wantsJson();

        // Username case-sensitive
        $admin = Admin::whereRaw('BINARY username = ?', [$credentials['username']])->first();

        if (!$admin || !Hash::check($credentials['password'], $admin->password)) {
            // Response jika gagal login
            $error = ['username' => 'Username atau password salah.'];

            if ($isApi) {
                return response()->json(['message' => $error['username']], 401);
            }

            return back()->withErrors($error);
        }

        // Login berhasil
        if ($isApi) {
            $token = $admin->createToken('admin-token')->plainTextToken;

            return response()->json([
                'message' => 'Login berhasil',
                'token' => $token,
                'admin' => $admin,
            ]);
        } else {
            Auth::guard('admin')->login($admin);
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }
    }

    // Logout Admin
    public function logout(Request $request)
    {
        if ($request->expectsJson()) {
            // Logout dari API
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Logout berhasil']);
        } else {
            // Logout dari Web
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login');
        }
    }
}
