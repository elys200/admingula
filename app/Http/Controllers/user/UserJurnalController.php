<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Jurnal;
use App\Models\Kategori_Gula;

class UserJurnalController extends Controller
{
    // Ambil jurnal milik user yg login
    public function index(Request $request)
    {
        $userId = auth()->id();

        $validated = $request->validate([
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $query = Jurnal::with('kategori')
            ->where('user_id', $userId)
            ->orderByDesc('date')
            ->orderByDesc('jam');

        if (!empty($validated['per_page'])) {
            $query->limit($validated['per_page']);
        }

        $data = $query->get();

        // Mapping ke response yg lebih rapi
        $mapped = $data->map(function ($item) {
            return [
                'id'           => $item->id,
                'date'         => $item->date,
                'waktu_makan'  => $item->waktu_makan,
                'jam'          => $item->jam,
                'total_gula'   => $item->total_gula,
                'total_kalori' => $item->total_kalori,
                'total_karbo'  => $item->total_karbo,
                'total_lemak'  => $item->total_lemak,
                'kategori'     => [
                    'id'   => $item->kategori?->id,
                    'nama' => $item->kategori?->nama,
                ],
                'status'       => ucfirst($item->kategori?->nama ?? '-'),
                'status_color' => $this->getStatusColor($item->kategori?->nama),
            ];
        });

        return response()->json([
            'data'  => $mapped,
            'total' => $mapped->count(),
        ]);
    }

    // Simpan jurnal baru untuk user
    public function store(Request $request)
    {
        $userId = auth()->id();

        $validated = $request->validate([
            'waktu_makan'   => 'required|string',
            'total_gula'    => 'required|numeric',
            'date'          => 'required|date',
            'jam'           => 'nullable|string',
            'total_kalori'  => 'nullable|numeric',
            'total_karbo'   => 'nullable|numeric',
            'total_lemak'   => 'nullable|numeric',
        ]);

        $kategori = Kategori_Gula::where('gula_min', '<=', $validated['total_gula'])
            ->where('gula_max', '>=', $validated['total_gula'])
            ->first();

        if (!$kategori) {
            return response()->json([
                'message' => 'Kategori gula tidak ditemukan untuk nilai ini.'
            ], 422);
        }

        $validated['user_id'] = $userId;
        $validated['kategori_gula_id'] = $kategori->id;

        $jurnal = Jurnal::create($validated);

        return response()->json($jurnal, 201);
    }

    // Warna status berdasarkan kategori
    private function getStatusColor($kategori)
    {
        return match (strtolower($kategori ?? '')) {
            'low'    => '#4CAF50',
            'normal' => '#FFC107',
            'high'   => '#F44336',
            default  => '#000000',
        };
    }
}
