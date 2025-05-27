<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BeritaRequest extends FormRequest
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
        'judul' => 'required|string|max:255',
        'sumber' => 'nullable|string|max:255',
        'deskripsi' => 'required|string',
        'penulis' => 'nullable|string|max:255',
        'tanggalterbit' => 'required|date',
        'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ];
    }
}
