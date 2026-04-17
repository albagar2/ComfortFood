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
        Schema::create('carrito', function (Blueprint $table) {
            $table->integer('id_carrito')->autoIncrement()->primary();
            $table->integer('id_cliente');
            $table->integer('id_menu');
            $table->integer('id_restaurante');
            $table->integer('cantidad')->default(1);
            $table->timestamps();

            $table->foreign('id_cliente')->references('id_cliente')->on('cliente')->onDelete('cascade');
            $table->foreign('id_menu')->references('id_menu')->on('menu')->onDelete('cascade');
            $table->foreign('id_restaurante')->references('id_restaurante')->on('restaurante')->onDelete('cascade');
            $table->unique(['id_cliente', 'id_menu']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carrito');
    }
};
