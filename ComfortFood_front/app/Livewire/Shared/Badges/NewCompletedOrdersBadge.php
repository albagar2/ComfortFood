<?php

namespace App\Livewire\Shared\Badges;

use App\Models\Pedido;
use Livewire\Attributes\On;
use Livewire\Component;

class NewCompletedOrdersBadge extends Component
{
    #[On('refresh-badges')]
    public function refresh()
    {
    }

    public function render()
    {
        if (auth()->check() && auth()->user()->isCliente()) {
            $clientId = auth()->user()->cliente->id_cliente;

            // Pedidos cancelados no vistos
            $cancelledCount = Pedido::where('id_cliente', $clientId)
                ->whereHas('estado', function ($q) {
                    $q->where('nombre_estado', 'Cancelado');
                })
                ->where('visto_completado', false)
                ->count();

            // Pedidos completados que requieren valoración (sin reseña)
            $completedCount = Pedido::where('id_cliente', $clientId)
                ->whereHas('estado', function ($q) {
                    $q->where('nombre_estado', 'Completado');
                })
                ->doesntHave('resena')
                ->count();
        }

        return view('livewire.shared.badges.new-completed-orders-badge', [
            'completedCount' => $completedCount,
            'cancelledCount' => $cancelledCount
        ]);
    }
}
