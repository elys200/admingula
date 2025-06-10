<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class UserChangePasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'current_password' => ['required'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'], 
            // 'confirmed' otomatis cek ada field 'new_password_confirmation'
        ];
    }

    public function messages()
    {
        return [
            'new_password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
            'new_password.min' => 'Kata sandi baru minimal 8 karakter.',
            'current_password.required' => 'Kata sandi lama harus diisi.',
        ];
    }
    
    // Optional validasi custom: cek current_password cocok dengan password user
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!Hash::check($this->current_password, auth()->user()->password)) {
                $validator->errors()->add('current_password', 'Kata sandi lama salah.');
            }
        });
    }
}
