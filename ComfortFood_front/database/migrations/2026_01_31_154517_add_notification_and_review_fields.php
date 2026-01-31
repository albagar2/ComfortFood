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
            $table->boolean('visto_completado')->default(false)->after('id_estado_pedido');
        });

        Schema::table('resena', function (Blueprint $table) {
            $table->boolean('visto')->default(false)->after('comentario');
            $table->integer('id_restaurante')->nullable()->after('id_cliente');
            $table->integer('id_menu')->nullable()->after('id_restaurante');

            $table->foreign('id_restaurante')->references('id_restaurante')->on('restaurante')->onDelete('cascade');
            $table->foreign('id_menu')->references('id_menu')->on('menu')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resena', function (Blueprint $table) {
            $table->dropForeign(['id_restaurante']);
            $table->dropForeign(['id_menu']);
            $table->dropColumn(['visto', 'id_restaurante', 'id_menu']);
        });

        Schema::table('pedido', function (Blueprint $table) {
            $table->dropColumn('visto_completado');
        });
    }
};
