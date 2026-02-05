<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pedido', function (Blueprint $table) {
            $table->index('visto_completado');
        });

        Schema::table('menu', function (Blueprint $table) {
            $table->index('esta_activo');
            $table->index('stock');
        });

        Schema::table('horario_restaurante', function (Blueprint $table) {
            $table->index('esta_abierto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedido', function (Blueprint $table) {
            $table->dropIndex(['visto_completado']);
        });

        Schema::table('menu', function (Blueprint $table) {
            $table->dropIndex(['esta_activo']);
            $table->dropIndex(['stock']);
        });

        Schema::table('horario_restaurante', function (Blueprint $table) {
            $table->dropIndex(['esta_abierto']);
        });
    }
};
