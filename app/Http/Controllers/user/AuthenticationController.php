<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class AuthenticationController extends Controller
{
    // Register
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        try {
            $user = User::create([
                'username'       => $validated['username'],
                'email'          => $validated['email'],
                'password'       => Hash::make($validated['password']),
                'umur'           => $validated['umur'],
                'berat_badan'    => $validated['berat_badan'],
                'jenis_kelamin'  => $validated['jenis_kelamin'],
            ]);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return response([
                    'message' => 'Username atau email sudah digunakan.'
                ], 409); 
            }

            throw $e;
        }

        $token = $user->createToken('user_token', ['user'])->plainTextToken;

        return response([
            'user'  => $user,
            'token' => $token,
        ], 201);
    }

    // Login
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        $user = User::where('username', $validated['username'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json(['message' => 'Login gagal'], 401);
        }

        $token = $user->createToken('user-token', ['user'])->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil.',
        ]);
    }
}
