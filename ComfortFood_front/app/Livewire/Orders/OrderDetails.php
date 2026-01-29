<?php

namespace App\Livewire\Orders;

use App\Models\EstadoPedido;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OrderDetails extends Component
{
    public Pedido $order;
    public $search = '';

    public function mount(Pedido $order)
    {
        $this->order = $order->load(['detalles.menu', 'estado', 'cliente.user', 'restaurante.user', 'resena']);
        
        $user = Auth::user();

        // Authorization check: User must be related to the order or admin
        if ($user->isRestaurante() && $this->order->id_restaurante !== $user->restaurante->id_restaurante) {
            abort(403);
        }
        if ($user->isCliente() && $this->order->id_cliente !== $user->cliente->id_cliente) {
             abort(403);
        }
    }

    public function acceptOrder()
    {
        $status = EstadoPedido::where('nombre_estado', 'Completado')->first();
        if ($status) {
             $this->order->update(['id_estado_pedido' => $status->id_estado_pedido]);
        }
    }

    public function cancelOrder()
    {
        $status = EstadoPedido::where('nombre_estado', 'Cancelado')->first();
         if ($status) {
             $this->order->update(['id_estado_pedido' => $status->id_estado_pedido]);
        }
    }

    public function render()
    {
        // For the "Lista pedidos" chips, we might want to show other recent orders for quick navigation
        // This is optional based on the specific design requirement "Lista pedidos"
        $quickAccessOrders = collect();
        if (Auth::user()->isRestaurante()) {
             $quickAccessOrders = Pedido::where('id_restaurante', Auth::user()->restaurante->id_restaurante)
                ->where('id_estado_pedido', '!=', EstadoPedido::where('nombre_estado', 'Completado')->first()->id_estado_pedido ?? 0) // Show pending/active
                 ->latest()
                 ->take(10)
                 ->get();
        }

        return view('livewire.orders.order-details', [
             'quickAccessOrders' => $quickAccessOrders
        ]);
    }
}
