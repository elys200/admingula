<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserUpdateProfileRequest;
use App\Http\Requests\UserUpdatePhotoRequest;
use App\Http\Requests\UserChangePasswordRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    // Menampilkan profil user
    public function show(Request $request)
    {
        $user = $request->user();

        $user->foto = $user->foto
            ? Storage::disk('public')->url($user->foto)
            : null;

        return response()->json($user);
    }

    // Mengupdate profil user (termasuk foto jika ada)
    public function update(UserUpdateProfileRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }

            $data['foto'] = $request->file('foto')->store('foto', 'public');
        } else {
            unset($data['foto']);
        }

        $user->update($data);

        $user->foto = $user->foto
            ? Storage::disk('public')->url($user->foto)
            : null;

        return response()->json([
            'message' => 'Profil berhasil diperbarui.',
            'user' => $user,
        ]);
    }

    // Hanya update foto profil
    public function updatePhoto(UserUpdatePhotoRequest $request)
    {
        $user = $request->user();

        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }

        $user->foto = $request->file('foto')->store('foto', 'public');
        $user->save();

        $user->foto = Storage::disk('public')->url($user->foto);

        return response()->json([
            'message' => 'Foto profil berhasil diperbarui.',
            'user' => $user,
        ]);
    }

    // Mengubah password
    public function changePassword(UserChangePasswordRequest $request)
    {
        $user = $request->user();

        $user->password = $request->new_password;
        $user->save();

        return response()->json([
            'message' => 'Kata sandi berhasil diubah.',
        ]);
    }
}
