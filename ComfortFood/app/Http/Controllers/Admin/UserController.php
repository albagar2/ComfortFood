<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\Admin\UpdateUserRequest;

class UserController extends Controller
{
    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['cliente', 'restaurante', 'rol']);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $user->load(['cliente', 'restaurante', 'rol']);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        // Update User
        $user->update([
            'nombre_completo' => $validated['nombre_completo'],
            'email' => $validated['email'],
            'id_rol' => $validated['id_rol'],
            'es_activo' => $validated['es_activo'],
        ]);

        // Update Profile
        if ($user->isRestaurante()) {
            $user->restaurante->update([
                'NIF' => $validated['NIF'],
                'direccion' => $validated['direccion'],
                'telefono' => $validated['telefono'],
                'tipo_cocina' => $validated['tipo_cocina'],
                'descripcion' => $validated['descripcion'],
            ]);
        } else {
            $user->cliente->update([
                'DNI' => $validated['DNI'],
                'direccion' => $validated['direccion'],
                'telefono' => $validated['telefono'],
            ]);
        }

        return redirect()->route('admin.users.show', $user)->with('success', 'Usuario actualizado correctamente.');
    }
}
