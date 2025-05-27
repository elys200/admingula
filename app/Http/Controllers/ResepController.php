<?php

namespace App\Http\Controllers;

use App\Models\Resep_Makanan;
use App\Http\Requests\ResepRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ResepController extends Controller
{
    public function view()
    {
        $resep = Resep_Makanan::all();
        return view('resep', compact('resep'));
    }

    public function index()
    {
        return response()->json(Resep_Makanan::all());
    }

    public function store(ResepRequest $request)
    {

        $data = $request->validated();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('resep', 'public');
        }

        $resep = Resep_Makanan::create($data);

        return response()->json([
    'message' => 'Resep berhasil disimpan',
    'data' => $resep
]);

    }

    public function show($id)
    {
        $resep = Resep_Makanan::findOrFail($id);
        return response()->json([
    'message' => 'Resep berhasil disimpan',
    'data' => $resep
]);

    }

    public function update(ResepRequest $request, $id)
    {
        $resep = Resep_Makanan::findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('gambar')) {
            if ($resep->gambar) {
                Storage::disk('public')->delete($resep->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('resep', 'public');
        }

        $resep->update($data);

        return response()->json([
    'message' => 'Resep berhasil disimpan',
    'data' => $resep
]);

    }

    public function destroy($id)
    {
        $resep = Resep_Makanan::findOrFail($id);

        if ($resep->gambar) {
            Storage::disk('public')->delete($resep->gambar);
        }

        $resep->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}

