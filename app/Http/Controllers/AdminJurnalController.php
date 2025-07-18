<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use Illuminate\Http\Request;

class AdminJurnalController extends Controller
{
    public function view()
    {
        return view('jurnal');
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 9);

        $query = Jurnal::with(['user:id,username', 'kategori:id,nama'])
            ->orderByDesc('date');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->whereHas('user', fn ($q) =>
                $q->where('username', 'like', "%{$search}%")
            );
        }

        $jurnal = $query->paginate($perPage);

        return response()->json([
            'data' => $jurnal->items(),
            'pagination' => [
                'current_page' => $jurnal->currentPage(),
                'last_page' => $jurnal->lastPage(),
                'per_page' => $jurnal->perPage(),
                'total' => $jurnal->total(),
                'from' => $jurnal->firstItem() ?? 0,
                'to' => $jurnal->lastItem(),
                'prev_page_url' => $jurnal->previousPageUrl(),
                'next_page_url' => $jurnal->nextPageUrl(),
            ],
        ]);
    }
}
