<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $table = 'admin';
    protected $fillable = [
        'username',
        'nama',
        'password',
    ];

    protected $hidden = [
        'password',
    ];
}
