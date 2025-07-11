<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jurnal;
use App\Models\Kategori_Gula;

class JurnalController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $jurnal = Jurnal::with('kategori')
            ->where('user_id', $request->user_id)
            ->orderByDesc('date')
            ->get()
            ->map(function ($item) {
                return [
                    'id'              => $item->id,
                    'tanggal'         => $item->date,
                    'waktu_makan'     => $item->waktu_makan,
                    'jam'             => $item->jam,
                    'total_gula'      => $item->total_gula,
                    'total_kalori'    => $item->total_kalori,
                    'total_karbo'     => $item->total_karbo,
                    'total_lemak'     => $item->total_lemak,
                    'kategori_gula_id'=> $item->kategori_gula_id,
                    'status'          => ucfirst($item->kategori->nama ?? '-'),
                    'status_color'    => $this->getStatusColor($item->kategori->nama ?? ''),
                ];
            });

        return response()->json($jurnal);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'       => 'required|exists:users,id',
            'waktu_makan'   => 'required|string',
            'total_gula'    => 'required|numeric',
            'date'          => 'required|date',
            'jam'           => 'nullable|string',
            'total_kalori'  => 'nullable|numeric',
            'total_karbo'   => 'nullable|numeric',
            'total_lemak'   => 'nullable|numeric',
        ]);

        // Tentukan kategori_gula_id berdasarkan total_gula
        $kategori = Kategori_Gula::where('gula_min', '<=', $validated['total_gula'])
            ->where('gula_max', '>=', $validated['total_gula'])
            ->first();

        if (!$kategori) {
            return response()->json([
                'message' => 'Kategori gula tidak ditemukan untuk nilai ini.'
            ], 422);
        }

        $validated['kategori_gula_id'] = $kategori->id;

        $jurnal = Jurnal::create($validated);

        return response()->json($jurnal, 201);
    }

    public function destroy($id)
    {
        $jurnal = Jurnal::findOrFail($id);
        $jurnal->delete();

        return response()->json(['message' => 'Jurnal berhasil dihapus.']);
    }

    private function getStatusColor($kategori)
    {
        return match (strtolower($kategori)) {
            'low'    => '#4CAF50',   // hijau
            'normal' => '#FFC107',   // kuning
            'high'   => '#F44336',   // merah
            default  => '#000000',   // hitam jika tidak dikenali
        };
    }
}
