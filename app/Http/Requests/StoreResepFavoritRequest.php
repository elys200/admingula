<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResepFavoritRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'resep_id' => 'required|exists:resep_makanan,id',
        ];
    }

    public function messages()
    {
        return [
            'resep_id.required' => 'Resep ID wajib diisi',
            'resep_id.exists' => 'Resep tidak ditemukan',
        ];
    }
}
