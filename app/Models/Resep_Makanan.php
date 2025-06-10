<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resep_Makanan extends Model
{
    protected $table = 'resep_makanan';
    protected $fillable = [
        'nama', 'deskripsi', 'panduan', 'total_kalori', 'total_karbohidrat', 'total_lemak','kadar_gula','bahan','tips', 'gambar',
];
    protected $casts = [
        'panduan' => 'array',
        'bahan' => 'array',
        'tips' => 'array',
    ];

}
