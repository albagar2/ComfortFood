<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Rol;
use App\Models\DiaSemana;
use App\Models\EstadoPedido;
use Illuminate\Support\Facades\Hash;
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
        Rol::updateOrCreate(['nombre_rol' => 'Administrador']);
        Rol::updateOrCreate(['nombre_rol' => 'Cliente']);
        Rol::updateOrCreate(['nombre_rol' => 'Restaurante']);

        // 2. Días de la semana
        $dias = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            7 => 'Domingo'
        ];
        foreach ($dias as $id => $nombre) {
            DiaSemana::updateOrCreate(['id_dia' => $id], ['nombre_dia' => $nombre]);
        }

        // 3. Estados de Pedido
        $estados = ['Pendiente', 'En Preparación', 'Entregado', 'Completado', 'Cancelado'];
        foreach ($estados as $estado) {
            EstadoPedido::updateOrCreate(['nombre_estado' => $estado]);
        }

        // 4. Usuarios Específicos
        // Administrador
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'nombre_completo' => 'Admin ComfortFood',
                'password' => Hash::make('password123'),
                'id_rol' => 1, // Administrador
                'es_activo' => true,
                'email_verified_at' => now(),
            ]
        );

        // Cliente Genérico
        $clienteUser = User::updateOrCreate(
            ['email' => 'cliente@gmail.com'],
            [
                'nombre_completo' => 'Cliente de Prueba',
                'password' => Hash::make('password123'),
                'id_rol' => 2, // Cliente
                'es_activo' => true,
                'email_verified_at' => now(),
            ]
        );
        $clienteUser->cliente()->updateOrCreate([], [
            'DNI' => '00000001A',
            'direccion' => 'Calle Falsa 123, Madrid',
            'telefono' => '600000001',
        ]);

        // Restaurante Genérico
        $restUser = User::updateOrCreate(
            ['email' => 'restaurante@gmail.com'],
            [
                'nombre_completo' => 'Restaurante de Prueba',
                'password' => Hash::make('password123'),
                'id_rol' => 3, // Restaurante
                'es_activo' => true,
                'email_verified_at' => now(),
            ]
        );
        $rest = $restUser->restaurante()->updateOrCreate([], [
            'tipo_cocina' => 'Mediterránea',
            'NIF' => 'B00000001',
            'direccion' => 'Avenida Principal 1, Barcelona',
            'telefono' => '930000001',
            'descripcion' => 'Restaurante de prueba con una excelente selección de platos mediterráneos.',
        ]);

        // Horarios L-V 8:00 - 21:00 para el restaurante de prueba
        for ($j = 1; $j <= 5; $j++) {
            $rest->horarios()->updateOrCreate(['id_dia' => $j], [
                'hora_apertura' => '08:00:00',
                'hora_cierre' => '21:00:00',
                'esta_abierto' => true,
            ]);
        }

        // Crear Menús para el restaurante de prueba
        \App\Models\Menu::factory()->count(5)->create([
            'id_restaurante' => $rest->id_restaurante
        ]);

        // 5. Datos Aleatorios Adicionales
        // Generar 2 restaurantes nuevos, cada uno con 3-5 menús
        for ($i = 0; $i < 2; $i++) {
            $email = fake()->unique()->safeEmail();
            $user = User::create([
                'nombre_completo' => fake()->name(),
                'email' => $email,
                'password' => Hash::make('password123'),
                'id_rol' => 3,
                'es_activo' => true,
                'email_verified_at' => now(),
            ]);

            $restRandom = \App\Models\Restaurante::create([
                'id_usuario' => $user->id_usuario,
                'tipo_cocina' => fake()->randomElement(['Italiana', 'Mexicana', 'Japonesa', 'Mediterránea', 'Vegetariana']),
                'NIF' => fake()->unique()->regexify('[0-9]{8}[A-Z]'),
                'direccion' => fake()->address,
                'telefono' => fake()->phoneNumber,
                'descripcion' => fake()->text(200),
            ]);

            // Horarios L-V 8:00 - 21:00
            for ($j = 1; $j <= 5; $j++) {
                $restRandom->horarios()->create([
                    'id_dia' => $j,
                    'hora_apertura' => '08:00:00',
                    'hora_cierre' => '21:00:00',
                    'esta_abierto' => true,
                ]);
            }

            \App\Models\Menu::factory()
                ->count(rand(3, 5))
                ->create(['id_restaurante' => $restRandom->id_restaurante]);
        }

        // Un cliente extra aleatorio
        $emailClient = fake()->unique()->safeEmail();
        $userClient = User::create([
            'nombre_completo' => fake()->name(),
            'email' => $emailClient,
            'password' => Hash::make('password123'),
            'id_rol' => 2,
            'es_activo' => true,
            'email_verified_at' => now(),
        ]);

        \App\Models\Cliente::create([
            'id_usuario' => $userClient->id_usuario,
            'DNI' => fake()->unique()->regexify('[0-9]{8}[A-Z]'),
            'direccion' => fake()->address,
            'telefono' => fake()->phoneNumber,
        ]);
    }
}
