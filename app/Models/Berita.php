<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    protected $table = 'berita';
    protected $fillable = [
        'judul', 'gambar', 'deskripsi', 'sumber', 'penulis', 'kategori', 'tanggalterbit', 'link'
    ];
    protected $dates = [
        'tanggalterbit'
    ];
}
