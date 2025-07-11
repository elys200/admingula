<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use Illuminate\Http\Request;

class AdminJurnalController extends Controller
{
    public function index()
    {
        $jurnal = Jurnal::with(['user', 'kategori'])->orderByDesc('date')->paginate(10);
        return view('jurnal', compact('jurnal'));
    }

    public function data(Request $request)
    {
        $query = Jurnal::with(['user:id,username', 'kategori:id,nama'])->orderByDesc('date');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->whereHas('user', fn ($q) => $q->where('username', 'like', "%{$search}%"));
        }

        $data = $query->paginate(10);

        return response()->json($data);
    }

}
