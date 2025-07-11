<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    protected $table = 'jurnal';
    protected $fillable = [
        'user_id', 'kategori_gula_id', 'waktu_makan', 'total_gula', 'date',
        'jam', 'total_kalori', 'total_karbo', 'total_lemak'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori_Gula::class, 'kategori_gula_id');
    }

    public function user()
{
    return $this->belongsTo(User::class);
}

}
