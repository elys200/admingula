<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resep_Makanan extends Model
{
    protected $table = 'resep_makanan';
    protected $fillable = [
        'nama', 'deskripsi', 'panduan', 'bahan', 'total_kalori', 'kadar_gula', 'gambar',
];
}
