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
    // Tampilkan halaman login web admin
    public function showLogin()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('dashboard');
        }

        return view('welcome');

    }

    // API: Register admin
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|unique:admin,username',
            'nama'     => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        $admin = Admin::create([
            'username' => $validated['username'],
            'nama'     => $validated['nama'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $admin->createToken('admin-token')->plainTextToken;

        return response()->json([
            'message' => 'Admin berhasil didaftarkan',
            'admin'   => $admin,
            'token'   => $token,
        ], 201);
    }

    // Login Web dan API
    public function login(AdminLoginRequest $request)
    {
        $credentials = $request->only('username', 'password');
        $isApi = $request->expectsJson() || $request->isJson() || $request->wantsJson();

        // Case-sensitive username check
        $admin = Admin::whereRaw('BINARY username = ?', [$credentials['username']])->first();

        if (!$admin || !Hash::check($credentials['password'], $admin->password)) {
            $error = ['username' => 'Username atau password salah.'];

            if ($isApi) {
                return response()->json(['message' => $error['username']], 401);
            }

            return back()->withErrors($error);
        }

        if ($isApi) {
            $token = $admin->createToken('admin-token')->plainTextToken;

            return response()->json([
                'message' => 'Login berhasil',
                'token'   => $token,
                'admin'   => $admin,
            ]);
        }

        // Web login
        Auth::guard('admin')->login($admin);
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    // Logout Web & API
    public function logout(Request $request)
    {
        if ($request->expectsJson()) {
            // API logout
            $request->user()->currentAccessToken()->delete();

            return response()->json(['message' => 'Logout berhasil']);
        }

        // Web logout
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
