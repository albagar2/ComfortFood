<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Get IDs for statuses
        $completado = DB::table('estado_pedido')->where('nombre_estado', 'Completado')->first();
        $entregado = DB::table('estado_pedido')->where('nombre_estado', 'Entregado')->first();

        if ($completado && $entregado) {
            // 2. Update orders with 'Completado' status to 'Entregado'
            DB::table('pedido')
                ->where('id_estado_pedido', $completado->id_estado_pedido)
                ->update(['id_estado_pedido' => $entregado->id_estado_pedido]);

            // 3. Delete 'Completado' status
            DB::table('estado_pedido')->where('id_estado_pedido', $completado->id_estado_pedido)->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-create 'Completado' status if it doesn't exist
        $exists = DB::table('estado_pedido')->where('nombre_estado', 'Completado')->exists();

        if (!$exists) {
            DB::table('estado_pedido')->insert([
                'nombre_estado' => 'Completado'
            ]);
        }
    }
};
