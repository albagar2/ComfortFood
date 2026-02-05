<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class TestOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure estados exist (no timestamps in this table)
        $estados = ['En espera', 'En preparación', 'Completado', 'Cancelado'];
        foreach ($estados as $estado) {
            if (!DB::table('estado_pedido')->where('nombre_estado', $estado)->exists()) {
                DB::table('estado_pedido')->insert(['nombre_estado' => $estado]);
            }
        }

        // Get roles
        $rolCliente = DB::table('rol')->where('nombre_rol', 'Cliente')->first();
        $rolRestaurante = DB::table('rol')->where('nombre_rol', 'Restaurante')->first();

        if (!$rolCliente || !$rolRestaurante) {
            $this->command->error('Roles no encontrados. Ejecuta primero: php artisan db:seed');
            return;
        }

        // Try to get existing users or create new ones
        $matildeUser = DB::table('usuario')->where('email', 'matilde@comfortfood.com')->first();
        $restauranteUser = DB::table('usuario')->where('email', 'labuena mesa@comfortfood.com')->first();

        // If users don't exist, create them
        if (!$matildeUser) {
            $matildeUserId = DB::table('usuario')->insertGetId([
                'nombre_completo' => 'Matilde García',
                'email' => 'matilde@comfortfood.com',
                'password' => Hash::make('password'),
                'id_rol' => $rolCliente->id_rol,
                'es_activo' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $matildeClienteId = DB::table('cliente')->insertGetId([
                'id_usuario' => $matildeUserId,
                'direccion' => 'C/ Juego de pelota, 23, Alcobendas',
                'telefono' => '655467345',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            
            $this->command->info("✅ Usuario Matilde creado");
        } else {
            $matildeCliente = DB::table('cliente')->where('id_usuario', $matildeUser->id_usuario)->first();
            if (!$matildeCliente) {
                $matildeClienteId = DB::table('cliente')->insertGetId([
                    'id_usuario' => $matildeUser->id_usuario,
                    'direccion' => 'C/ Juego de pelota, 23, Alcobendas',
                    'telefono' => '655467345',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                $matildeClienteId = $matildeCliente->id_cliente;
            }
        }

        if (!$restauranteUser) {
            $restauranteUserId = DB::table('usuario')->insertGetId([
                'nombre_completo' => 'La Buena Mesa',
                'email' => 'labuena mesa@comfortfood.com',
                'password' => Hash::make('password'),
                'id_rol' => $rolRestaurante->id_rol,
                'es_activo' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Generate unique NIF
            $nif = 'B' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
            
            $restauranteId = DB::table('restaurante')->insertGetId([
                'id_usuario' => $restauranteUserId,
                'tipo_cocina' => 'Mediterránea',
                'descripcion' => 'Buen lugar para hacer pedidos o incluso venir a nuestro restaurante LA BUENA MESA desde 1992',
                'NIF' => $nif,
                'direccion' => 'C/ Gran Vía, 45, Madrid',
                'telefono' => '957606423',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            
            $this->command->info("✅ Restaurante La Buena Mesa creado");
        } else {
            $restaurante = DB::table('restaurante')->where('id_usuario', $restauranteUser->id_usuario)->first();
            if (!$restaurante) {
                // Generate unique NIF
                $nif = 'B' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
                
                $restauranteId = DB::table('restaurante')->insertGetId([
                    'id_usuario' => $restauranteUser->id_usuario,
                    'tipo_cocina' => 'Mediterránea',
                    'descripcion' => 'Buen lugar para hacer pedidos o incluso venir a nuestro restaurante LA BUENA MESA desde 1992',
                    'NIF' => $nif,
                    'direccion' => 'C/ Gran Vía, 45, Madrid',
                    'telefono' => '957606423',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                $restauranteId = $restaurante->id_restaurante;
            }
        }

        // Create menu item
        $menu1Id = DB::table('menu')->insertGetId([
            'id_restaurante' => $restauranteId,
            'nombre_menu' => 'Menú Mediterráneo',
            'descripcion_menu' => 'Delicioso menú con sabores del mediterráneo',
            'precio' => 15.50,
            'plato_principal' => 'Paella Valenciana',
            'segundo_plato' => 'Ensalada Griega',
            'postre' => 'Tarta de Limón',
            'bebida' => 'Agua mineral',
            'esta_activo' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Get estado "En preparación"
        $estadoPreparacion = DB::table('estado_pedido')->where('nombre_estado', 'En preparación')->first();

        // Create the order
        $pedidoId = DB::table('pedido')->insertGetId([
            'id_cliente' => $matildeClienteId,
            'id_restaurante' => $restauranteId,
            'precio_total' => 46.50,
            'id_estado_pedido' => $estadoPreparacion->id_estado_pedido,
            'direccion_entrega' => 'C/ Juego de pelota, 23, Alcobendas',
            'created_at' => Carbon::now()->subHours(2),
            'updated_at' => Carbon::now()->subHours(2),
        ]);

        // Create order details
        DB::table('detalle_pedido')->insert([
            'id_pedido' => $pedidoId,
            'id_menu' => $menu1Id,
            'cantidad' => 3,
            'precio_unitario' => 15.50,
            'created_at' => Carbon::now()->subHours(2),
            'updated_at' => Carbon::now()->subHours(2),
        ]);

        $this->command->info('');
        $this->command->info('✅ ¡Pedido de prueba creado exitosamente!');
        $this->command->info("   📦 Pedido #$pedidoId");
        $this->command->info("   👤 Cliente: Matilde García");
        $this->command->info("   🍽️  Restaurante: La Buena Mesa");
        $this->command->info("   💰 Total: 46.50€");
        $this->command->info("   📊 Estado: En preparación");
        $this->command->info('');
        $this->command->info('📝 Credenciales de prueba:');
        $this->command->info('   Cliente: matilde@comfortfood.com / password');
        $this->command->info('   Restaurante: labuena mesa@comfortfood.com / password');
    }
}
