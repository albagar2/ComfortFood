<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Clean table to avoid constraint violations during migration
        DB::table('favorito')->truncate();

        Schema::table('favorito', function (Blueprint $table) {
            // Drop foreign keys first to allow index removal
            try {
                $table->dropForeign('favorito_id_cliente_foreign');
                $table->dropForeign('favorito_id_restaurante_foreign');
            } catch (\Exception $e) {
            }

            // Now we can drop the unique index
            try {
                $table->dropUnique('favorito_id_cliente_id_restaurante_unique');
            } catch (\Exception $e) {
            }

            // Re-define id_menu as NOT NULL and unsigned (to match PRI key of menu)
            // We use change() which works in Laravel 11+ directly for MySQL
            $table->integer('id_menu')->unsigned()->nullable(false)->change();

            // Re-add foreign keys (this recreates simple indexes for these columns)
            $table->foreign('id_cliente')->references('id_cliente')->on('cliente')->onDelete('cascade');
            $table->foreign('id_restaurante')->references('id_restaurante')->on('restaurante')->onDelete('cascade');

            // Add new unique constraint for FAVORITE MENUS
            $table->unique(['id_cliente', 'id_menu'], 'favorito_id_cliente_id_menu_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('favorito', function (Blueprint $table) {
            try {
                $table->dropUnique('favorito_id_cliente_id_menu_unique');
            } catch (\Exception $e) {
            }

            $table->integer('id_menu')->unsigned()->nullable()->change();

            try {
                $table->unique(['id_cliente', 'id_restaurante'], 'favorito_id_cliente_id_restaurante_unique');
            } catch (\Exception $e) {
            }
        });
    }
};
