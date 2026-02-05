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
        Schema::table('carrito', function (Blueprint $table) {
            $table->text('observaciones')->nullable();
        });
        Schema::table('detalle_pedido', function (Blueprint $table) {
            $table->text('observaciones')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carrito', function (Blueprint $table) {
            $table->dropColumn('observaciones');
        });
        Schema::table('detalle_pedido', function (Blueprint $table) {
            $table->dropColumn('observaciones');
        });
    }
};
