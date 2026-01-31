<?php

namespace App\Livewire\Orders;

use App\Models\EstadoPedido;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OrderDetails extends Component
{
    public Pedido $order;
    public $rating = 5;
    public $comment = '';

    protected $rules = [
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:500',
    ];

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

    public function advanceStatus()
    {
        $currentStatus = $this->order->estado->nombre_estado ?? '';
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
                $this->order->update(['id_estado_pedido' => $status->id_estado_pedido]);
                $this->order->refresh();
            }
        }
    }

    protected $listeners = ['cancelOrderConfirmed' => 'cancelOrder'];

    public function confirmCancel()
    {
        $this->dispatch(
            'show-confirmation',
            title: '¿Cancelar Pedido?',
            message: '¿Estás seguro de que deseas cancelar este pedido? Esta acción no se puede deshacer.',
            confirmAction: 'cancelOrderConfirmed',
            confirmParams: []
        );
    }

    public function cancelOrder()
    {
        $status = EstadoPedido::where('nombre_estado', 'Cancelado')->first();

        if ($status) {
            // Strict validation: Only 'Pendiente' can be cancelled
            if ($this->order->estado->nombre_estado !== 'Pendiente') {
                return;
            }

            $this->order->update(['id_estado_pedido' => $status->id_estado_pedido]);
            $this->order->refresh();
        }
    }

    public function saveReview()
    {
        if (!auth()->user()->isCliente() || $this->order->estado->nombre_estado !== 'Completado') {
            return;
        }

        $this->validate();

        \App\Models\Resena::create([
            'id_pedido' => $this->order->id_pedido,
            'id_cliente' => auth()->user()->cliente->id_cliente,
            'puntuacion' => $this->rating,
            'comentario' => $this->comment,
        ]);

        $this->order->load('resena');

        $this->dispatch('review-saved');
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
