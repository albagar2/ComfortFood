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
        $count = 0;
        if (auth()->check() && auth()->user()->isCliente()) {
            $count = Pedido::where('id_cliente', auth()->user()->cliente->id_cliente)
                ->whereHas('estado', function ($q) {
                    $q->whereIn('nombre_estado', ['Completado', 'Cancelado']);
                })
                ->where('visto_completado', false)
                ->count();
        }

        return view('livewire.shared.badges.new-completed-orders-badge', ['count' => $count]);
    }
}
