<?php

namespace App\Livewire;

use App\Models\Pedido;
use Livewire\Component;

class NewCompletedOrdersBadge extends Component
{
    #[Livewire\Attributes\On('refresh-badges')]
    public function refresh()
    {
    }

    public function render()
    {
        $count = 0;
        if (auth()->check() && auth()->user()->isCliente()) {
            $count = Pedido::where('id_cliente', auth()->user()->cliente->id_cliente)
                ->whereHas('estado', function ($q) {
                    $q->where('nombre_estado', 'Completado');
                })
                ->where('visto_completado', false)
                ->count();
        }

        return view('livewire.new-completed-orders-badge', ['count' => $count]);
    }
}
