<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->route('user');

        $rules = [
            'nombre_completo' => 'required|string|max:255',
            'email' => 'required|email|unique:usuario,email,' . $user->id_usuario . ',id_usuario',
            'id_rol' => 'required|exists:rol,id_rol',
            'es_activo' => 'required|boolean',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
        ];

        if ($user->isRestaurante()) {
            $rules['NIF'] = 'required|string|max:20';
            $rules['tipo_cocina'] = 'required|string|max:255';
            $rules['descripcion'] = 'nullable|string';
        } else {
            $rules['DNI'] = 'required|string|max:20';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nombre_completo.required' => 'El nombre completo es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.unique' => 'Este correo electrónico ya está en uso.',
            'id_rol.required' => 'El rol es obligatorio.',
            'direccion.required' => 'La dirección es obligatoria.',
            'telefono.required' => 'El teléfono es obligatorio.',
        ];
    }
}
