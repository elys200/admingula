<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResepController extends Controller
{
    public function index()
    {
        return view('resep');
    }
}
