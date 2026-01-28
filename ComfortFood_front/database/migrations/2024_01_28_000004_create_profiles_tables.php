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
        Schema::create('cliente', function (Blueprint $table) {
            $table->integer('id_cliente')->autoIncrement()->primary();
            $table->integer('id_usuario')->unique();
            $table->string('url_imagen_perfil', 500)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('tarjeta_mock', 20)->nullable();
            $table->timestamps();

            $table->foreign('id_usuario')->references('id_usuario')->on('usuario')->onDelete('cascade');
        });

        Schema::create('restaurante', function (Blueprint $table) {
            $table->integer('id_restaurante')->autoIncrement()->primary();
            $table->integer('id_usuario')->unique();
            $table->string('tipo_cocina', 100);
            $table->string('redes_sociales', 255)->nullable();
            $table->text('descripcion')->nullable();
            $table->string('cuenta_bancaria_mock', 100)->nullable();
            $table->string('NIF', 20)->unique();
            $table->string('url_imagen_perfil', 500)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->timestamps();

            $table->foreign('id_usuario')->references('id_usuario')->on('usuario')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurante');
        Schema::dropIfExists('cliente');
    }
};
