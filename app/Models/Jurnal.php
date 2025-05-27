<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    protected $table = 'jurnal';
    protected $fillable = [
        'judul', 'gambar', 'deskripsi', 'sumber', 'penulis', 'tanggalterbit'
    ];
    protected $dates = [
        'tanggalterbit'
    ];
}
