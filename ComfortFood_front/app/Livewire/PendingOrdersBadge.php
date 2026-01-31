<?php

namespace App\Livewire;

use App\Models\Pedido;
use Livewire\Component;

class PendingOrdersBadge extends Component
{
    public function render()
    {
        $count = 0;
        if (auth()->check() && auth()->user()->isRestaurante()) {
            $count = Pedido::where('id_restaurante', auth()->user()->restaurante->id_restaurante)
                ->whereHas('estado', function ($q) {
                    $q->where('nombre_estado', 'Pendiente');
                })
                ->count();
        }

        return view('livewire.pending-orders-badge', ['count' => $count]);
    }
}
