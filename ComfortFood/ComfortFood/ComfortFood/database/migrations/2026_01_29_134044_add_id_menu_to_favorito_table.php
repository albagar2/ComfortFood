<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('favorito', 'id_menu')) {
            try {
                Schema::table('favorito', function (Blueprint $table) {
                    $table->integer('id_menu')->unsigned()->nullable()->after('id_restaurante');
                });
            } catch (\Exception $e) {}
        }
        
        try {
            DB::statement('ALTER TABLE favorito MODIFY id_restaurante INT UNSIGNED NULL');
        } catch (\Exception $e) {}
        
        try {
            Schema::table('favorito', function (Blueprint $table) {
                $table->dropUnique('favorito_id_cliente_id_restaurante_unique');
            });
        } catch (\Exception $e) {}

        try {
            Schema::table('favorito', function (Blueprint $table) {
                $table->foreign('id_menu')->references('id_menu')->on('menu')->onDelete('cascade');
            });
        } catch (\Exception $e) {}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('favorito', function (Blueprint $table) {
                $table->dropForeign(['id_menu']);
                $table->dropColumn('id_menu');
            });
        } catch (\Exception $e) {}
        
        try {
            DB::statement('ALTER TABLE favorito MODIFY id_restaurante INT UNSIGNED NOT NULL');
            Schema::table('favorito', function (Blueprint $table) {
                $table->unique(['id_cliente', 'id_restaurante'], 'favorito_id_cliente_id_restaurante_unique');
            });
        } catch (\Exception $e) {}
    }
};
