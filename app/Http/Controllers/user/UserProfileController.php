<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserUpdateProfileRequest;
use App\Http\Requests\UserUpdatePhotoRequest;
use App\Http\Requests\UserChangePasswordRequest;

class UserProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        if ($user->foto) {
            $user->foto = \Storage::disk('public')->url($user->foto);
        } else {
            $user->foto = null;
        }

        return response()->json($user);
    }

    // Melakukan update profil user
    public function update(UserUpdateProfileRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            if ($user->foto && \Storage::disk('public')->exists($user->foto)) {
                \Storage::disk('public')->delete($user->foto);
            }

            $path = $request->file('foto')->store('foto', 'public');
            $data['foto'] = $path;
        } else {
            unset($data['foto']);
        }

        $user->update($data);

        if ($user->foto) {
            $user->foto = \Storage::disk('public')->url($user->foto);
        } else {
            $user->foto = null;
        }

        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'user' => $user,
        ]);
    }

    // Melakukan update foto
    public function updatePhoto(UserUpdatePhotoRequest $request)
    {
        $user = $request->user();

        if ($user->foto && \Storage::disk('public')->exists($user->foto)) {
            \Storage::disk('public')->delete($user->foto);
        }

        $path = $request->file('foto')->store('foto', 'public');

        $user->foto = $path;
        $user->save();

        $user->foto = \Storage::disk('public')->url($user->foto);

        return response()->json([
            'message' => 'Foto profil berhasil diperbarui.',
            'user' => $user,
        ]);
    }

    //Mengubah kata sandi
    public function changePassword(UserChangePasswordRequest $request)
    {
        $user = $request->user();

        $user->password = bcrypt($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'Kata sandi berhasil diubah.',
        ]);
    }
}
