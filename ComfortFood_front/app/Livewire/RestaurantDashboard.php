<?php

namespace App\Livewire;

use App\Models\EstadoPedido;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class RestaurantDashboard extends Component
{
    public $search = '';

    public function acceptOrder($orderId)
    {
        $order = Pedido::where('id_pedido', $orderId)->first();
        
        // Find "Completado" status
        $status = EstadoPedido::where('nombre_estado', 'Completado')->first();
        
        if ($order && $status) {
             $order->update(['id_estado_pedido' => $status->id_estado_pedido]);
        }
    }

    public function cancelOrder($orderId)
    {
        $order = Pedido::where('id_pedido', $orderId)->first();
        
        // Find "Cancelado" status
        $status = EstadoPedido::where('nombre_estado', 'Cancelado')->first();
        
        if ($order && $status) {
             $order->update(['id_estado_pedido' => $status->id_estado_pedido]);
        }
    }

    public function render()
    {
        $user = Auth::user();

        // Ensure user is authorized
        if (!$user || !$user->isRestaurante()) {
            abort(403, 'Unauthorized access');
        }

        $query = Pedido::where('id_restaurante', $user->restaurante->id_restaurante)
            ->with(['detalles.menu', 'estado', 'cliente.user'])
            ->latest();

        if ($this->search) {
             $query->where(function($q) {
                $q->where('id_pedido', 'like', '%' . $this->search . '%')
                  ->orWhereHas('cliente.user', function($sq) {
                      $sq->where('nombre_completo', 'like', '%' . $this->search . '%');
                  });
             });
        }

        return view('livewire.restaurant-dashboard', [
            'orders' => $query->get()
        ]);
    }
}
