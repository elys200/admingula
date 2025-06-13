<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KategoriGulaRequest extends FormRequest
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
            'nama' => 'required|string|in:low,normal,high',
            'gula_min' => 'required|numeric|min:0|max:999999.99',
            'gula_max' => 'nullable|numeric|gte:gula_min',
            'deskripsi' => 'nullable|string|max:255',
        ];
    }
}
