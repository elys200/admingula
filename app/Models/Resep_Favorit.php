<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resep_Favorit extends Model
{
    protected $table = 'resep_favorit';

    protected $fillable = ['user_id', 'resep_id'];

    protected $casts = [
        'user_id' => 'string',
        'resep_id' => 'string',
    ];

    public function resep()
    {
        return $this->belongsTo(Resep_Makanan::class, 'resep_id');
    }

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
