<?php

namespace App\Livewire;

use App\Models\Pedido;
use App\Models\EstadoPedido;
use App\Models\Menu;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class RestaurantOrders extends Component
{
    public $activeTab = 'pendiente';
    public $orders = [];

    public function mount()
    {
        $this->loadOrders();
    }

    public function loadOrders()
    {
        $restaurante = auth()->user()->restaurante;
        if (!$restaurante) {
            $this->orders = [];
            return;
        }

        $query = Pedido::where('id_restaurante', $restaurante->id_restaurante)
            ->with(['cliente.user', 'estado', 'detalles.menu'])
            ->latest();

        // Filter by tab
        if ($this->activeTab !== 'todos') {
            $estado = EstadoPedido::where('nombre_estado', ucfirst($this->activeTab))->first();
            if ($estado) {
                $query->where('id_estado_pedido', $estado->id_estado_pedido);
            }
        }

        $this->orders = $query->get()->toArray();
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->loadOrders();
    }

    public function acceptOrder($pedidoId)
    {
        try {
            DB::beginTransaction();

            $pedido = Pedido::with('detalles.menu')->find($pedidoId);
            if (!$pedido) {
                throw new \Exception('Pedido no encontrado');
            }

            // Validate stock for all items
            foreach ($pedido->detalles as $detalle) {
                if ($detalle->cantidad > $detalle->menu->stock) {
                    throw new \Exception("No hay suficiente stock para {$detalle->menu->nombre_menu}");
                }
            }

            // Deduct stock
            foreach ($pedido->detalles as $detalle) {
                $menu = Menu::find($detalle->id_menu);
                $menu->stock -= $detalle->cantidad;
                $menu->save();
            }

            // Update order status to "Aceptado"
            $estadoAceptado = EstadoPedido::where('nombre_estado', 'Aceptado')->first();
            if (!$estadoAceptado) {
                throw new \Exception('Estado no encontrado');
            }

            $pedido->id_estado_pedido = $estadoAceptado->id_estado_pedido;
            $pedido->save();

            DB::commit();

            $this->loadOrders();
            session()->flash('success', 'Pedido aceptado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }
    }

    public function rejectOrder($pedidoId)
    {
        try {
            $pedido = Pedido::find($pedidoId);
            if (!$pedido) {
                throw new \Exception('Pedido no encontrado');
            }

            // Update order status to "Cancelado"
            $estadoCancelado = EstadoPedido::where('nombre_estado', 'Cancelado')->first();
            if (!$estadoCancelado) {
                throw new \Exception('Estado no encontrado');
            }

            $pedido->id_estado_pedido = $estadoCancelado->id_estado_pedido;
            $pedido->save();

            $this->loadOrders();
            session()->flash('success', 'Pedido rechazado');

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function updateStatus($pedidoId, $newStatus)
    {
        try {
            $pedido = Pedido::find($pedidoId);
            if (!$pedido) {
                throw new \Exception('Pedido no encontrado');
            }

            $estado = EstadoPedido::where('nombre_estado', $newStatus)->first();
            if (!$estado) {
                throw new \Exception('Estado no encontrado');
            }

            $pedido->id_estado_pedido = $estado->id_estado_pedido;
            $pedido->save();

            $this->loadOrders();
            session()->flash('success', "Estado actualizado a {$newStatus}");

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.restaurant-orders');
    }
}
