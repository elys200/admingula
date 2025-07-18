<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jurnal;
use App\Models\Berita;
use App\Models\Resep_Makanan;
use App\Models\Kategori_Gula;
class DashboardController extends Controller
{
    public function index()
    {
        $jurnalCount = Jurnal::count();
        $beritaCount = Berita::count();
        $resepCount = Resep_Makanan::count();
        $kategoriGulaCount = Kategori_Gula::count();

        return view('dashboard', compact('jurnalCount', 'beritaCount', 'resepCount', 'kategoriGulaCount'));
    }
}
