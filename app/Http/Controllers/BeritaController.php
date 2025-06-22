<?php

namespace App\Http\Controllers;

use App\Http\Requests\BeritaRequest;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    // Tampilkan halaman view dengan pagination
    public function view()
    {
        $berita = Berita::paginate(4); // Default 4 per halaman
        return view('berita', compact('berita'));
    }

    // API: Ambil data berita dengan search & pagination
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 4);
        $query = Berita::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('judul', 'like', "%{$search}%");
        }

        $berita = $query->paginate($perPage);

        return response()->json([
            'data' => $berita->items(),
            'current_page' => $berita->currentPage(),
            'last_page' => $berita->lastPage(),
            'per_page' => $berita->perPage(),
            'total' => $berita->total(),
            'from' => $berita->firstItem(),
            'to' => $berita->lastItem(),
            'prev_page_url' => $berita->previousPageUrl(),
            'next_page_url' => $berita->nextPageUrl(),
        ]);
    }

        public function getAll()
    {
        $beritas = Berita::all();
        return response()->json($beritas);
    }

    // Simpan data berita baru
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

    // Ambil detail berita
    public function show($id)
    {
        $berita = Berita::findOrFail($id);
        return response()->json([
            'message' => 'Detail berita berhasil dimuat',
            'data' => $berita
        ]);
    }

    // Perbarui berita
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

    // Optional: Ambil berdasarkan kategori
    public function kategori($kategori)
    {
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
}
