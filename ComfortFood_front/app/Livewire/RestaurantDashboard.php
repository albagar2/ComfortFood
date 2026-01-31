<?php

namespace App\Livewire;

use App\Models\EstadoPedido;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class RestaurantDashboard extends Component
{
    public $search = '';

    public function advanceStatus($orderId)
    {
        $order = Pedido::where('id_pedido', $orderId)->first();

        if (!$order) {
            return;
        }

        $currentStatus = $order->estado->nombre_estado ?? '';
        $nextStatusName = match ($currentStatus) {
            'Pendiente' => 'En Preparación',
            'En Preparación' => 'Entregado',
            'Entregado' => 'Completado',
            default => null
        };

        if ($nextStatusName) {
            // Find status case-insensitively or exactly as per DB
            $status = EstadoPedido::where('nombre_estado', $nextStatusName)->first();

            // Fallback for case mismatch if needed, though exact match is safer
            if (!$status) {
                // Try loose matching just in case
                $status = EstadoPedido::where('nombre_estado', 'LIKE', $nextStatusName)->first();
            }

            if ($status) {
                $order->update(['id_estado_pedido' => $status->id_estado_pedido]);
            }
        }
    }

    protected $listeners = ['cancelOrderConfirmed' => 'cancelOrder'];

    public function confirmCancel($orderId)
    {
        $this->dispatch(
            'show-confirmation',
            title: '¿Cancelar Pedido?',
            message: '¿Estás seguro de que deseas cancelar este pedido? Esta acción no se puede deshacer.',
            confirmAction: 'cancelOrderConfirmed',
            confirmParams: [$orderId]
        );
    }

    public function cancelOrder($orderId)
    {
        $order = Pedido::where('id_pedido', $orderId)->first();

        // Find "Cancelado" status
        $status = EstadoPedido::where('nombre_estado', 'Cancelado')->first();

        if ($order && $status) {
            // Strict validation: Only 'Pendiente' can be cancelled
            if ($order->estado->nombre_estado !== 'Pendiente') {
                $this->dispatch('notify', 'No se puede cancelar el pedido en este estado.'); // Optional: if you have a notification system
                return;
            }

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
            $query->where(function ($q) {
                $q->where('id_pedido', 'like', '%' . $this->search . '%')
                    ->orWhereHas('cliente.user', function ($sq) {
                        $sq->where('nombre_completo', 'like', '%' . $this->search . '%');
                    });
            });
        }

        return view('livewire.restaurant-dashboard', [
            'orders' => $query->get()
        ]);
    }
}
