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
            'new_password' => ['required', 'string', 'min:6', 'confirmed'],
        ];
    }

    public function messages()
    {
        return [
            'new_password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
            'new_password.min' => 'Kata sandi baru minimal 6 karakter.',
            'current_password.required' => 'Kata sandi lama harus diisi.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user = auth()->user();

            if (!Hash::check($this->current_password, (string) $user->password)) {
                $validator->errors()->add('current_password', 'Kata sandi lama salah.');
            }

            if ($this->current_password === $this->new_password) {
                $validator->errors()->add('new_password', 'Kata sandi baru tidak boleh sama dengan kata sandi lama.');
            }
        });
    }
}
