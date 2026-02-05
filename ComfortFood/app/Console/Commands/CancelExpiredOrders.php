<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CancelExpiredOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cancel-expired-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancela automáticamente los pedidos que han superado el tiempo máximo de respuesta.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredOrders = \App\Models\Pedido::expired()->get();

        if ($expiredOrders->isEmpty()) {
            $this->info('No hay pedidos expirados para cancelar.');
            return;
        }

        $canceledStatus = \App\Models\EstadoPedido::where('nombre_estado', 'Cancelado')->first();

        if (!$canceledStatus) {
            $this->error('No se pudo encontrar el estado "Cancelado" en la base de datos.');
            return;
        }

        foreach ($expiredOrders as $order) {
            /** @var \App\Models\Pedido $order */
            $order->update([
                'id_estado_pedido' => $canceledStatus->id_estado_pedido,
                'visto_completado' => false,
            ]);

            $this->info("Pedido #{$order->id_pedido} cancelado por expiración de tiempo.");
            \Illuminate\Support\Facades\Log::info("Pedido #{$order->id_pedido} cancelado automáticamente por expiración (10 min).");
        }

        $this->info('Proceso de cancelación de pedidos expirados completado.');
    }
}
