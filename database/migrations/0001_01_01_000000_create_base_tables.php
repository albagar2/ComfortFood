<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rol', function (Blueprint $table) {
            $table->integer('id_rol')->autoIncrement()->primary();
            $table->string('nombre_rol', 50)->unique();
        });

        Schema::create('dia_semana', function (Blueprint $table) {
            $table->integer('id_dia')->primary();
            $table->string('nombre_dia', 20)->unique();
        });

        Schema::create('estado_pedido', function (Blueprint $table) {
            $table->integer('id_estado_pedido')->autoIncrement()->primary();
            $table->string('nombre_estado', 50)->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estado_pedido');
        Schema::dropIfExists('dia_semana');
        Schema::dropIfExists('rol');
    }
};
