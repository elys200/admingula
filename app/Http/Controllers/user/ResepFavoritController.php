<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Resep_Favorit;
use App\Http\Requests\StoreResepFavoritRequest;

class ResepFavoritController extends Controller
{
    // Ambil semua resep favorit user login
    public function show()
    {
        $user_id = auth()->id();
        $favorits = Resep_Favorit::where('user_id', $user_id)->with('resep')->get();

        return response()->json($favorits);
    }

    // Toggle resep favorit
    public function toggle(StoreResepFavoritRequest $request)
    {
        $user_id = auth()->id();
        $resep_id = $request->input('resep_id');

        $favorit = Resep_Favorit::where('user_id', $user_id)
            ->where('resep_id', $resep_id)
            ->first();

        if ($favorit) {
            Resep_Favorit::where('user_id', $user_id)
                ->where('resep_id', $resep_id)
                ->delete();
            return response()->json(['message' => 'Resep dihapus dari favorit']);
        } else {
            $favorit = Resep_Favorit::create([
                'user_id' => $user_id,
                'resep_id' => $resep_id,
            ]);
            return response()->json([
                'message' => 'Resep ditambahkan ke favorit',
                'data' => $favorit->load('resep')
            ], 201);
        }
    }
}
