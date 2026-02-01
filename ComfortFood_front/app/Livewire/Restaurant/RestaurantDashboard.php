<?php

namespace App\Livewire\Restaurant;

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
            $status = EstadoPedido::where('nombre_estado', $nextStatusName)->first();

            if (!$status) {
                $status = EstadoPedido::where('nombre_estado', 'LIKE', $nextStatusName)->first();
            }

            if ($status) {
                $order->update(['id_estado_pedido' => $status->id_estado_pedido]);
            }
        }
    }

    protected $listeners = [
        'cancelOrderConfirmed' => 'cancelOrder',
        'refresh-badges' => '$refresh'
    ];

    public function confirmCancel($orderId)
    {
        $this->dispatch(
            'show-confirmation',
            title: '¿Cancelar Pedido?',
            message: '¿Estás seguro de cancelar este pedido?',
            confirmAction: 'cancelOrderConfirmed',
            confirmParams: [$orderId]
        );
    }

    public function cancelOrder($orderId)
    {
        $order = Pedido::where('id_pedido', $orderId)->first();
        $status = EstadoPedido::where('nombre_estado', 'Cancelado')->first();

        if ($order && $status) {
            // Solo permitir cancelar si está Pendiente
            if ($order->estado->nombre_estado !== 'Pendiente') {
                return;
            }

            $order->update([
                'id_estado_pedido' => $status->id_estado_pedido,
                'visto_completado' => false // Reset to notify client
            ]);

            $this->dispatch('refresh-badges');
        }
    }

    public $filterStatus = 'all';

    public function setFilter($status)
    {
        $this->filterStatus = $status;
    }

    public function render()
    {
        $user = Auth::user();

        if (!$user || !$user->isRestaurante()) {
            abort(403, 'Unauthorized access');
        }

        $query = Pedido::where('id_restaurante', $user->restaurante->id_restaurante)
            ->with(['detalles.menu', 'estado', 'cliente.user'])
            ->whereDate('created_at', now()->today());

        if ($this->filterStatus !== 'all') {
            $query->whereHas('estado', function ($q) {
                $q->where('nombre_estado', $this->filterStatus);
            });
            $query->orderBy('created_at', 'asc');
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('id_pedido', 'like', '%' . $this->search . '%')
                    ->orWhereHas('cliente.user', function ($sq) {
                        $sq->where('nombre_completo', 'like', '%' . $this->search . '%');
                    });
            });
        }

        $orders = $query->get();

        if ($this->filterStatus === 'all') {
            $priorityMap = [
                'Pendiente' => 1,
                'En Preparación' => 2,
                'Entregado' => 3,
                'Completado' => 4,
                'Cancelado' => 5
            ];

            $orders = $orders->sort(function ($a, $b) use ($priorityMap) {
                $statusA = $a->estado->nombre_estado;
                $statusB = $b->estado->nombre_estado;
                $prioA = $priorityMap[$statusA] ?? 99;
                $prioB = $priorityMap[$statusB] ?? 99;

                if ($prioA === $prioB) {
                    return $a->created_at <=> $b->created_at;
                }
                return $prioA <=> $prioB;
            });
        }

        return view('livewire.restaurant.restaurant-dashboard', [
            'orders' => $orders
        ]);
    }
}
