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
        $resep = Resep_Makanan::all();
        return view('resep', compact('resep'));
    }
    
    // Fungsi untuk menampilkan semua resep makanan dalam format JSON
    public function index()
    {
        return response()->json(Resep_Makanan::all());
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