<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $this->usuario],
        ];
        if ($this->isMethod('post') || $this->filled('password')) {
            $rules['password'] = ['required', 'confirmed', 'min:8'];
        }
        return $rules;
    }
}
