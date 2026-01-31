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

    public $filterStatus = 'all';

    public function setFilter($status)
    {
        $this->filterStatus = $status;
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
            ->whereDate('created_at', now()->today()); // Only today's orders

        // Filter by specific status text via relationship
        if ($this->filterStatus !== 'all') {
            $query->whereHas('estado', function ($q) {
                $q->where('nombre_estado', $this->filterStatus);
            });
            // If specific status, just sort by time (oldest first)
            $query->orderBy('created_at', 'asc');
        } else {
            // "All": Sort by Priority then Time
            // Join with estados to sort by name mapped to priority
            // We use a raw select/order for performance or simple collection sort.
            // Given small daily volume, collection sort is cleaner and safer than complex raw SQL with joins here.

            // Fetch first then sort
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
                    return $a->created_at <=> $b->created_at; // Oldest first
                }
                return $prioA <=> $prioB;
            });
        }

        return view('livewire.restaurant.restaurant-dashboard', [
            'orders' => $orders
        ]);
    }
}
