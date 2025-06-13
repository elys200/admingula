<?php

namespace App\Http\Controllers;

use App\Models\Kategori_Gula;
use App\Http\Requests\KategoriGulaRequest;

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

        $kategori->update($data);

        return response()->json([
            'message' => 'Kategori berhasil disimpan',
            'data' => $kategori
        ]);
    }

    public function destroy($id)
    {
        $kategori = Kategori_Gula::findOrFail($id);
        $kategori->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
