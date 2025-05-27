<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;

class AuthenticationController extends Controller
{
    public function register(RegisterRequest $request)
{
    $validated = $request->validated();

    $user = User::create([
    'username' => $validated['username'],
    'email' => $validated['email'],
    'password' => Hash::make($validated['password']),
    'umur' => $validated['umur'],
    'berat_badan' => $validated['berat_badan'],
    'jenis_kelamin' => $validated['jenis_kelamin'],
]);


    $token = $user->createToken('sweet_sense')->plainTextToken;

    return response([
        'user' => $user,
        'token' => $token,
    ], 201);
}

    public function login(LoginRequest $request)
{
    $validated = $request->validated();

    $user = User::where('username', $validated['username'])->first();

    if (!$user || !Hash::check($validated['password'], $user->password)) {
        return response([
            'message' => 'Invalid credentials'
        ], 422);
    }

    $token = $user->createToken('sweet_sense')->plainTextToken;

    return response([
        'user' => $user,
        'token' => $token,
    ], 200);
}

}
