<?php

namespace App\Livewire\Shared\Badges;

use App\Models\Pedido;
use Livewire\Attributes\On;
use Livewire\Component;

class PendingOrdersBadge extends Component
{
    #[On('refresh-badges')]
    public function refresh()
    {
    }

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

        return view('livewire.shared.badges.pending-orders-badge', ['count' => $count]);
    }
}
