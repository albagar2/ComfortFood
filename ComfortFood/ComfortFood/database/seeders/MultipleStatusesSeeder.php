<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MultipleStatusesSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Get/Create Roles
        $rolCliente = DB::table('rol')->where('nombre_rol', 'Cliente')->first();
        $rolRestaurante = DB::table('rol')->where('nombre_rol', 'Restaurante')->first();

        // 2. Get/Create Users
        $clienteUser = DB::table('usuario')->where('email', 'cliente@gmail.com')->first();
        $restauranteUser = DB::table('usuario')->where('email', 'restaurante@gmail.com')->first();

        if (!$clienteUser || !$restauranteUser) {
            $this->command->error('Usuarios base no encontrados. Por favor, ejecuta php artisan db:seed primero.');
            return;
        }

        $cliente = DB::table('cliente')->where('id_usuario', $clienteUser->id_usuario)->first();
        $restaurante = DB::table('restaurante')->where('id_usuario', $restauranteUser->id_usuario)->first();

        if (!$cliente || !$restaurante) {
            $this->command->error('Cliente o Restaurante no encontrado.');
            return;
        }

        // 3. Get/Create Menu
        $menu = DB::table('menu')->where('id_restaurante', $restaurante->id_restaurante)->first();
        if (!$menu) {
            $menuId = DB::table('menu')->insertGetId([
                'id_restaurante' => $restaurante->id_restaurante,
                'nombre_menu' => 'Menú Degustación',
                'descripcion_menu' => 'Un poco de todo',
                'precio' => 25.00,
                'esta_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $menuId = $menu->id_menu;
        }

        // 4. Create orders for each status
        $estados = DB::table('estado_pedido')->get();
        if ($estados->isEmpty()) {
            $nombresEstados = ['En espera', 'En preparación', 'Completado', 'Cancelado'];
            foreach ($nombresEstados as $nombre) {
                DB::table('estado_pedido')->insert(['nombre_estado' => $nombre]);
            }
            $estados = DB::table('estado_pedido')->get();
        }

        foreach ($estados as $estado) {
            $pedidoId = DB::table('pedido')->insertGetId([
                'id_cliente' => $cliente->id_cliente,
                'id_restaurante' => $restaurante->id_restaurante,
                'precio_total' => $menu->precio ?? 25.00,
                'id_estado_pedido' => $estado->id_estado_pedido,
                'direccion_entrega' => $cliente->direccion ?? 'Calle Falsa 123',
                'created_at' => Carbon::now()->subMinutes(rand(10, 500)),
                'updated_at' => Carbon::now(),
            ]);

            DB::table('detalle_pedido')->insert([
                'id_pedido' => $pedidoId,
                'id_menu' => $menuId,
                'cantidad' => 1,
                'precio_unitario' => $menu->precio ?? 25.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $this->command->info("✅ Pedido creado con estado: {$estado->nombre_estado}");
        }
    }
}
