<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    // Aturan validasi input profil
    public function rules()
    {
        $userId = $this->user()->id;

        return [
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($userId)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'umur' => ['required', 'integer', 'min:1', 'max:120'],
            'berat_badan' => ['required', 'numeric', 'min:1'],
            'jenis_kelamin' => ['required', Rule::in(['Pria', 'Wanita'])],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ];
    }
}
