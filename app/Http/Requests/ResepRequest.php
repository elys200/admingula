<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResepRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama' => 'nullable|string|max:255',
            'deskripsi' => 'required|string',
            'panduan' => 'nullable|string',
            'total_kalori' => 'required|numeric|min:0|max:999999.99',
            'total_karbohidrat' => 'required|numeric|min:0|max:999999.99',
            'total_lemak' => 'required|numeric|min:0|max:999999.99',
            'kadar_gula' => 'required|numeric|min:0|max:999999.99',
            'bahan' => 'nullable|string',
            'tips' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ];
    }
}
