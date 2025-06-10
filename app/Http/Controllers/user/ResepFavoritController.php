<?php
namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Resep_Favorit;
use App\Http\Requests\StoreResepFavoritRequest;

class ResepFavoritController extends Controller
{
    // Tampilkan resep favorit user login
    public function show()
    {
        $user_id = auth()->id();

        $favorits = Resep_Favorit::where('user_id', $user_id)->with('resep')->get();
        return response()->json($favorits);
    }

    // Tambah resep favorit user login
    public function store(StoreResepFavoritRequest $request)
{
    $user_id = auth()->id(); 
    $resep_id = $request->input('resep_id');

    $exists = Resep_Favorit::where('user_id', $user_id)
        ->where('resep_id', $resep_id)
        ->exists();

    if ($exists) {
        return response()->json(['message' => 'Resep sudah ada di favorit'], 409);
    }

    $favorit = Resep_Favorit::create([
        'user_id' => $user_id,
        'resep_id' => $resep_id,
    ]);

    return response()->json(['message' => 'Resep berhasil ditambahkan ke favorit', 'data' => $favorit], 201);
}

}
