<?php

namespace App\Http\Controllers;

use App\Models\Resep_Makanan;
use App\Http\Requests\ResepRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ResepController extends Controller
{
    //Fungsi untuk menampilkan halaman resep makanan
    public function view()
    {
        $resep = Resep_Makanan::paginate(4);
        return view('resep', compact('resep'));
    }
    
    // Fungsi untuk menampilkan semua resep makanan dalam format JSON
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 4);
        $query = Resep_Makanan::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('nama', 'like', "%{$search}%");
        }

        $resep = $query->paginate($perPage);

        return response()->json([
            'data' => $resep->items(),
            'current_page' => $resep->currentPage(),
            'last_page' => $resep->lastPage(),
            'per_page' => $resep->perPage(),
            'total' => $resep->total(),
            'from' => $resep->firstItem(),
            'to' => $resep->lastItem(),
            'prev_page_url' => $resep->previousPageUrl(),
            'next_page_url' => $resep->nextPageUrl(),
        ]);
    }

    public function getAll()
    {
        $reseps = Resep_Makanan::all();
        return response()->json($reseps);
    }

    // Fungsi untuk mengambil 3 resep makanan terbaru untuk dashboard
    public function getTop3()
    {
        $reseps = Resep_Makanan::latest()->take(3)->get();

        return response()->json($reseps);
    }
    // Fungsi untuk menyimpan resep makanan baru
    public function store(ResepRequest $request)
    {
        $data = $request->validated();

        // Konversi data dari JSON string ke array untuk penyimpanan
        $data['panduan'] = $data['panduan'] ?? [];
        $data['bahan'] = $data['bahan'] ?? [];
        $data['tips'] = $data['tips'] ?? [];

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('resep', 'public');
        }

        $resep = Resep_Makanan::create($data);

        return response()->json(['message' => 'Resep berhasil disimpan', 'data' => $resep]);
    }

    // Fungsi untuk menampilkan detail resep makanan berdasarkan ID
    public function show($id)
    {
        $resep = Resep_Makanan::findOrFail($id);

        return response()->json(['message' => 'Data berhasil dimuat', 'data' => $resep]);
    }
    
    // Fungsi untuk memperbarui resep makanan berdasarkan ID
    public function update(ResepRequest $request, $id)
    {
        $resep = Resep_Makanan::findOrFail($id);
        $data = $request->validated();

        // Konversi data dari JSON string ke array untuk penyimpanan
        $data['panduan'] = $data['panduan'] ?? [];
        $data['bahan'] = $data['bahan'] ?? [];
        $data['tips'] = $data['tips'] ?? [];

        if ($request->hasFile('gambar')) {
            if ($resep->gambar) {
                Storage::disk('public')->delete($resep->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('resep', 'public');
        }

        $resep->update($data);

        return response()->json(['message' => 'Resep berhasil diperbarui', 'data' => $resep]);
    }

    // Fungsi untuk menghapus resep makanan berdasarkan ID
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