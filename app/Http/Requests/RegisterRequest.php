<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
    protected function prepareForValidation(): void
    {
        $this->merge([
            'jenis_kelamin' => ucfirst(strtolower($this->jenis_kelamin)),
        ]);
    }
    
    public function rules(): array
    {
        return [
            'username' => 'required|string|min:4|alpha_dash|unique:users,username',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'umur' => 'required|integer|min:1|max:120',
            'berat_badan' => 'required|numeric|min:1|max:500',
            'jenis_kelamin' => 'required|in:Pria,Wanita',
        ];
    }
}
