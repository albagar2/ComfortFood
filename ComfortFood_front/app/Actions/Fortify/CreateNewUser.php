<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'password' => $this->passwordRules(),
        ])->validate();

        // Obtener el ID del rol
        $rolNombre = strtolower($input['rol']) === 'restaurante' ? 'Restaurante' : 'Cliente';
        $rol = \App\Models\Rol::where('nombre_rol', $rolNombre)->firstOrFail();

        return \DB::transaction(function () use ($input, $rol) {
            $user = User::create([
                'nombre_completo' => $input['nombre_completo'],
                'email' => $input['email'],
                'password' => $input['password'],
                'id_rol' => $rol->id_rol,
                'es_activo' => true,
            ]);

            if ($rol->nombre_rol === 'Cliente') {
                $user->cliente()->create([
                    'direccion' => $input['direccion_cliente'] ?? null,
                    'telefono' => $input['telefono_cliente'] ?? null,
                ]);
            } else {
                $user->restaurante()->create([
                    'tipo_cocina' => $input['tipo_cocina'],
                    'direccion' => $input['direccion'] ?? null,
                    'telefono' => $input['telefono'] ?? null,
                    'NIF' => $input['NIF'],
                    'descripcion' => $input['descripcion'] ?? null,
                ]);
            }

            return $user;
        });
    }
}
