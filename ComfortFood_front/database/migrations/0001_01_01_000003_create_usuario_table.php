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
        Schema::create('usuario', function (Blueprint $table) {
            $table->integer('id_usuario')->autoIncrement()->primary();
            $table->integer('id_rol');
            $table->string('email', 100)->unique();
            $table->string('password', 255); // We use 'password' for Laravel compatibility
            $table->string('nombre_completo', 150);
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('es_activo')->default(true);
            $table->rememberToken();
            $table->timestamps(); // includes fecha_registro equivalent

            $table->foreign('id_rol')->references('id_rol')->on('rol')->onDelete('cascade');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index(); // Laravel expects user_id here for Auth sessions
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('usuario');
    }
};
