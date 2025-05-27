<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori_Gula extends Model
{
    protected $table = 'kategori_gula';
    protected $fillable = [
        'nama', 'gula_min', 'gula_max', 'deskripsi',
    ];
}
