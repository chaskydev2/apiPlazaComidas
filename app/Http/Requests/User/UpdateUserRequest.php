<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id'); // porque tu ruta es .../users/update/{id}

        return [
            'usuario'   => [
                'sometimes','string','max:255',
                Rule::unique('users','usuario')->ignore($id, 'idusuario'),
            ],
            'email'     => [
                'sometimes','string','email','max:255',
                Rule::unique('users','email')->ignore($id, 'idusuario'),
            ],
            'password'  => ['sometimes','nullable','string','min:8','confirmed'],
            'idempresa' => ['sometimes','nullable','integer','exists:empresas,idempresa'],
        ];
    }
}