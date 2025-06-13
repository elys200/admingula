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

    // Ambil semua data berita
    public function index()
    {
        return response()->json(Berita::all());
    }

    // Ambil berita berdasarkan kategori (Rekomendasi, Terbaru, Fakta Terpilih)
    public function kategori($kategori)
    {
        // Validasi kategori yang diperbolehkan
        $allowedKategori = ['Rekomendasi', 'Terbaru', 'Fakta Terpilih'];

        if (!in_array($kategori, $allowedKategori)) {
            return response()->json([
                'message' => 'Kategori tidak valid.',
            ], 400);
        }

        $berita = Berita::where('kategori', $kategori)->get();

        return response()->json([
            'message' => "Berita kategori: $kategori",
            'data' => $berita
        ]);
    }

    // Simpan berita baru
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

    // Tampilkan detail berita berdasarkan ID
    public function show($id)
    {
        $berita = Berita::findOrFail($id);
        return response()->json([
            'message' => 'Detail berita berhasil dimuat',
            'data' => $berita
        ]);
    }

    // Update berita berdasarkan ID
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
            'message' => 'Berita berhasil diperbarui',
            'data' => $berita
        ]);
    }

    // Hapus berita
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
