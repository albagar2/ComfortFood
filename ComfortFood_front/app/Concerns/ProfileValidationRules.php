<?php

namespace App\Concerns;

use App\Models\User;
use Illuminate\Validation\Rule;

trait ProfileValidationRules
{
    /**
     * Get the validation rules used to validate user profiles.
     *
     * @return array<string, array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>>
     */
    protected function profileRules(?int $userId = null): array
    {
        return [
            'nombre_completo' => ['required', 'string', 'max:150'],
            'email' => $this->emailRules($userId),
            'rol' => ['required', 'string', Rule::in(['cliente', 'restaurante'])],

            // Campos de Cliente/Restaurante (opcionales dependiendo del contexto)
            'direccion' => ['nullable', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:20'],

            // Solo Restaurante
            'tipo_cocina' => ['required_if:rol,restaurante', 'nullable', 'string', 'max:100'],
            'NIF' => ['required_if:rol,restaurante', 'nullable', 'string', 'max:20', Rule::unique('restaurante', 'NIF')],
            'descripcion' => ['nullable', 'string'],
        ];
    }

    /**
     * Get the validation rules used to validate user names.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function nameRules(): array
    {
        return ['required', 'string', 'max:255'];
    }

    /**
     * Get the validation rules used to validate user emails.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function emailRules(?int $userId = null): array
    {
        return [
            'required',
            'string',
            'email',
            'max:255',
            $userId === null
            ? Rule::unique(User::class)
            : Rule::unique(User::class)->ignore($userId, 'id_usuario'),
        ];
    }
}
