<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // agrega tu control de permisos si corresponde
    }

    public function rules(): array
    {
        return [
            'usuario'      => ['required','string','max:255','unique:users,usuario'],
            'email'        => ['required','string','email','max:255','unique:users,email'],
            'password'     => ['required','string','min:8','confirmed'], // requiere password_confirmation
            'idempresa'    => ['nullable','integer','exists:empresas,idempresa'],
        ];
    }
}