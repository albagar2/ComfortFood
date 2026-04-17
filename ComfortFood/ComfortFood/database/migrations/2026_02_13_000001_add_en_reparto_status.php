<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if 'En Reparto' exists
        $exists = DB::table('estado_pedido')->where('nombre_estado', 'En Reparto')->exists();

        if (!$exists) {
            DB::table('estado_pedido')->insert([
                'nombre_estado' => 'En Reparto'
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Delete 'En Reparto' status
        DB::table('estado_pedido')->where('nombre_estado', 'En Reparto')->delete();
    }
};
