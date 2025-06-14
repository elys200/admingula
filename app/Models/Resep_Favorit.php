<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resep_Favorit extends Model
{
    protected $table = 'resep_favorit';

    protected $fillable = ['user_id', 'resep_id'];

    public $incrementing = false; 
    protected $primaryKey = ['user_id', 'resep_id'];
    public $timestamps = true;
    protected function setKeysForSaveQuery($query)
    {
        return $query->where('user_id', $this->user_id)
                    ->where('resep_id', $this->resep_id);
    }

    public function resep()
    {
        return $this->belongsTo(Resep_Makanan::class, 'resep_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
