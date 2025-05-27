<?php

namespace App\Http\Controllers;

use App\Http\Requests\BeritaRequest;
use App\Models\Berita;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    public function view()
    {
        $berita = Berita::all();
        return view('berita', compact('berita'));
    }

    public function index()
    {
        return response()->json(Berita::all());
    }

    public function store(BeritaRequest $request)
    {

        $data = $request->validated();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('berita', 'public');
        }

        $berita = Berita::create($data);

        return response()->json([
    'message' => 'Berita berhasil disimpan',
    'data' => $berita
]);

    }

    public function show($id)
    {
        $berita = Berita::findOrFail($id);
        return response()->json([
    'message' => 'Berita berhasil disimpan',
    'data' => $berita
]);

    }

    public function update(BeritaRequest $request, $id)
    {
        $berita = Berita::findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('gambar')) {
            if ($berita->gambar) {
                Storage::disk('public')->delete($berita->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('berita', 'public');
        }

        $berita->update($data);

        return response()->json([
    'message' => 'Berita berhasil disimpan',
    'data' => $berita
]);

    }

    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);

        if ($berita->gambar) {
            Storage::disk('public')->delete($berita->gambar);
        }

        $berita->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
