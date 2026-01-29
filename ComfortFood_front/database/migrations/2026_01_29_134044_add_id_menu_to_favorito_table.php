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
        Schema::table('favorito', function (Blueprint $table) {
            $table->foreignId('id_menu')->nullable()->after('id_restaurante')->constrained('menu', 'id_menu')->onDelete('cascade');
            $table->dropForeign(['id_restaurante']);
        });

        DB::statement('ALTER TABLE favorito MODIFY id_restaurante BIGINT UNSIGNED NULL');

        Schema::table('favorito', function (Blueprint $table) {
             $table->foreign('id_restaurante')->references('id_restaurante')->on('restaurante')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('favorito', function (Blueprint $table) {
            $table->dropForeign(['id_menu']);
            $table->dropColumn('id_menu');
            $table->dropForeign(['id_restaurante']);
        });

        DB::statement('ALTER TABLE favorito MODIFY id_restaurante BIGINT UNSIGNED NOT NULL');

        Schema::table('favorito', function (Blueprint $table) {
             $table->foreign('id_restaurante')->references('id_restaurante')->on('restaurante')->onDelete('cascade');
        });
    }
};
