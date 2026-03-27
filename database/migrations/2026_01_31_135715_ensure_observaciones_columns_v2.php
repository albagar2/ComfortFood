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
        if (Schema::hasTable('carrito')) {
            Schema::table('carrito', function (Blueprint $table) {
                if (!Schema::hasColumn('carrito', 'observaciones')) {
                    $table->text('observaciones')->nullable();
                }
            });
        }

        if (Schema::hasTable('detalle_pedido')) {
            Schema::table('detalle_pedido', function (Blueprint $table) {
                if (!Schema::hasColumn('detalle_pedido', 'observaciones')) {
                    $table->text('observaciones')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('carrito')) {
            Schema::table('carrito', function (Blueprint $table) {
                if (Schema::hasColumn('carrito', 'observaciones')) {
                    $table->dropColumn('observaciones');
                }
            });
        }

        if (Schema::hasTable('detalle_pedido')) {
            Schema::table('detalle_pedido', function (Blueprint $table) {
                if (Schema::hasColumn('detalle_pedido', 'observaciones')) {
                    $table->dropColumn('observaciones');
                }
            });
        }
    }
};
