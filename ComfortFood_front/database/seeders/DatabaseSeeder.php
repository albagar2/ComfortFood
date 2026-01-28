<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Roles
        \App\Models\Rol::updateOrCreate(['nombre_rol' => 'Administrador']);
        \App\Models\Rol::updateOrCreate(['nombre_rol' => 'Cliente']);
        \App\Models\Rol::updateOrCreate(['nombre_rol' => 'Restaurante']);

        // 2. Días de la semana
        $dias = [
            1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles',
            4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado', 7 => 'Domingo'
        ];
        foreach ($dias as $id => $nombre) {
            \App\Models\DiaSemana::updateOrCreate(['id_dia' => $id], ['nombre_dia' => $nombre]);
        }

        // 3. Estados de Pedido
        $estados = ['Pendiente', 'En Preparación', 'Viene en Camino', 'Entregado', 'Cancelado'];
        foreach ($estados as $estado) {
            \App\Models\EstadoPedido::updateOrCreate(['nombre_estado' => $estado]);
        }

        // 4. Usuario Administrador
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'nombre_completo' => 'Admin ComfortFood',
                'password' => \Illuminate\Support\Facades\Hash::make('12345678m'),
                'id_rol' => 1,
                'es_activo' => true,
            ]
        );
    }
}
