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
        Schema::create('menu', function (Blueprint $table) {
            $table->integer('id_menu')->autoIncrement()->primary();
            $table->integer('id_restaurante');
            $table->string('nombre_menu', 100);
            $table->text('descripcion_menu')->nullable();
            $table->decimal('precio', 6, 2);
            $table->string('url_foto', 500)->nullable();
            $table->string('plato_principal', 100)->nullable();
            $table->string('segundo_plato', 100)->nullable();
            $table->string('postre', 100)->nullable();
            $table->string('bebida', 100)->nullable();
            $table->text('propiedades_nutricionales')->nullable();
            $table->boolean('esta_activo')->default(true);
            $table->timestamps();

            $table->foreign('id_restaurante')->references('id_restaurante')->on('restaurante')->onDelete('cascade');
        });

        Schema::create('horario_restaurante', function (Blueprint $table) {
            $table->integer('id_horario')->autoIncrement()->primary();
            $table->integer('id_restaurante');
            $table->integer('id_dia');
            $table->time('hora_apertura');
            $table->time('hora_cierre');
            $table->boolean('esta_abierto')->default(true);
            $table->timestamps();

            $table->foreign('id_restaurante')->references('id_restaurante')->on('restaurante')->onDelete('cascade');
            $table->foreign('id_dia')->references('id_dia')->on('dia_semana');
            $table->unique(['id_restaurante', 'id_dia', 'hora_apertura'], 'uk_horario_dia');
        });

        Schema::create('favorito', function (Blueprint $table) {
            $table->integer('id_favorito')->autoIncrement()->primary();
            $table->integer('id_cliente');
            $table->integer('id_restaurante');
            $table->timestamps();

            $table->foreign('id_cliente')->references('id_cliente')->on('cliente')->onDelete('cascade');
            $table->foreign('id_restaurante')->references('id_restaurante')->on('restaurante')->onDelete('cascade');
            $table->unique(['id_cliente', 'id_restaurante']);
        });

        Schema::create('pedido', function (Blueprint $table) {
            $table->integer('id_pedido')->autoIncrement()->primary();
            $table->integer('id_cliente');
            $table->integer('id_restaurante');
            $table->decimal('precio_total', 7, 2);
            $table->integer('id_estado_pedido');
            $table->string('direccion_entrega', 255);
            $table->timestamps(); // includes fecha_hora_pedido

            $table->foreign('id_cliente')->references('id_cliente')->on('cliente');
            $table->foreign('id_restaurante')->references('id_restaurante')->on('restaurante');
            $table->foreign('id_estado_pedido')->references('id_estado_pedido')->on('estado_pedido');
        });

        Schema::create('detalle_pedido', function (Blueprint $table) {
            $table->integer('id_detalle')->autoIncrement()->primary();
            $table->integer('id_pedido');
            $table->integer('id_menu');
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 6, 2); // changed from precio_unidad for clarity
            $table->timestamps();

            $table->foreign('id_pedido')->references('id_pedido')->on('pedido')->onDelete('cascade');
            $table->foreign('id_menu')->references('id_menu')->on('menu');
            $table->unique(['id_pedido', 'id_menu']);
        });

        Schema::create('resena', function (Blueprint $table) {
            $table->integer('id_resena')->autoIncrement()->primary();
            $table->integer('id_pedido')->unique();
            $table->integer('id_cliente');
            $table->tinyInteger('puntuacion');
            $table->text('comentario')->nullable();
            $table->timestamps(); // includes fecha_resena

            $table->foreign('id_pedido')->references('id_pedido')->on('pedido');
            $table->foreign('id_cliente')->references('id_cliente')->on('cliente');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resena');
        Schema::dropIfExists('detalle_pedido');
        Schema::dropIfExists('pedido');
        Schema::dropIfExists('favorito');
        Schema::dropIfExists('horario_restaurante');
        Schema::dropIfExists('menu');
    }
};
