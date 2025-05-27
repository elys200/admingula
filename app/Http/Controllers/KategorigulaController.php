<?php

namespace App\Http\Controllers;

use App\Models\Kategori_Gula;
use App\Http\Requests\KategoriGulaRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class KategorigulaController extends Controller
{
    public function view()
    {
        $kategori = Kategori_Gula::all();
        return view('kategori_gula', compact('kategori'));
    }

    public function index()
    {
        return response()->json(Kategori_Gula::all());
    }

    public function store(KategoriGulaRequest $request)
    {

        $data = $request->validated();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('kategori_gula', 'public');
        }

        $kategori = Kategori_Gula::create($data);

        return response()->json([
    'message' => 'Kategori berhasil disimpan',
    'data' => $kategori
]);

    }

    public function show($id)
    {
        $kategori = Kategori_Gula::findOrFail($id);
        return response()->json([
    'message' => 'Kategori berhasil disimpan',
    'data' => $kategori
]);

    }

    public function update(KategoriGulaRequest $request, $id)
    {
        $kategori = Kategori_Gula::findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('gambar')) {
            if ($kategori->gambar) {
                Storage::disk('public')->delete($kategori->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('kategori_gula', 'public');
        }

        $kategori->update($data);

        return response()->json([
    'message' => 'Kategori berhasil disimpan',
    'data' => $kategori
]);

    }

    public function destroy($id)
    {
        $kategori = Kategori_Gula::findOrFail($id);

        if ($kategori->gambar) {
            Storage::disk('public')->delete($kategori->gambar);
        }

        $kategori->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}

