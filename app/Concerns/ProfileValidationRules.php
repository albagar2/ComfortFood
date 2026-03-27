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
    protected function profileRules(?int $userId = null, ?string $rol = null, bool $isProfileComponent = false): array
    {
        $rol = strtolower($rol ?? '');

        $rules = [
            'nombre_completo' => ['required', 'string', 'min:3', 'max:150'],
            'email' => $this->emailRules($userId),
            'rol' => ['required', 'string', Rule::in(['cliente', 'restaurante'])],
        ];

        // Validación dinámica según el rol
        if ($rol === 'restaurante') {
            $rules['direccion'] = ['required', 'string', 'min:10', 'max:255'];
            $rules['telefono'] = ['required', 'string', 'min:10', 'max:20', 'regex:/^\+?[0-9\s\-]+$/'];
            $rules['tipo_cocina'] = ['required', 'string', 'min:3', 'max:100'];
            $rules['descripcion'] = ['required', 'string', 'min:10', 'max:1000'];
            $rules['NIF'] = [
                'nullable',
                'string',
                'max:20',
                $userId ? Rule::unique('restaurante', 'NIF')->ignore($userId, 'id_usuario') : Rule::unique('restaurante', 'NIF')
            ];
        } elseif ($rol === 'cliente') {
            $direccionKey = $isProfileComponent ? 'direccion' : 'direccion_cliente';
            $telefonoKey = $isProfileComponent ? 'telefono' : 'telefono_cliente';

            $rules[$direccionKey] = ['required', 'string', 'min:10', 'max:255'];
            $rules[$telefonoKey] = ['required', 'string', 'min:10', 'max:20', 'regex:/^\+?[0-9\s\-]+$/'];
            $rules['tarjeta_mock'] = ['nullable', 'string', 'max:19'];
        }

        return $rules;
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
