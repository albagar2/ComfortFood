<?php

namespace App\Livewire\Orders;

use App\Models\EstadoPedido;
use App\Models\Pedido;
use App\Mail\DeliveredOrderMail;
use App\Mail\PreparingOrderMail;
use App\Mail\OutForDeliveryMail;
use App\Mail\CancelledOrderMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\On;
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
            'En Preparación' => 'En Reparto',
            'En Reparto' => 'Entregado',
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

                if ($nextStatusName === 'En Preparación') {
                    try {
                        $this->order->loadMissing('cliente.user');
                        if ($this->order->cliente && $this->order->cliente->user && $this->order->cliente->user->email) {
                            Mail::to($this->order->cliente->user->email)->send(new PreparingOrderMail($this->order));
                        }
                    } catch (\Exception $e) {
                        Log::error('Error enviando email de preparación: ' . $e->getMessage());
                    }
                }

                if ($nextStatusName === 'En Reparto') {
                    try {
                        $this->order->loadMissing('cliente.user');
                        if ($this->order->cliente && $this->order->cliente->user && $this->order->cliente->user->email) {
                            Mail::to($this->order->cliente->user->email)->send(new OutForDeliveryMail($this->order));
                        }
                    } catch (\Exception $e) {
                        Log::error('Error enviando email de reparto: ' . $e->getMessage());
                    }
                }

                if ($nextStatusName === 'Entregado') {
                    // Mark as complete for badges logic (formerly 'Completado' concept)
                    $this->order->update(['visto_completado' => false]);

                    try {
                        $this->order->loadMissing('cliente.user', 'restaurante.user');
                        if ($this->order->cliente && $this->order->cliente->user && $this->order->cliente->user->email) {
                            Mail::to($this->order->cliente->user->email)->send(new DeliveredOrderMail($this->order));
                        }
                    } catch (\Exception $e) {
                        Log::error('Error enviando email de entrega: ' . $e->getMessage());
                    }
                }
            }
        }
    }

    #[On('cancelOrderConfirmed')]
    public function cancelOrder()
    {
        $status = EstadoPedido::where('nombre_estado', 'Cancelado')->first();

        if ($status) {
            // Solo permitir cancelar si está Pendiente
            if ($this->order->estado->nombre_estado !== 'Pendiente') {
                return;
            }

            $this->order->update([
                'id_estado_pedido' => $status->id_estado_pedido,
                'visto_completado' => false // Reset to notify client
            ]);

            try {
                $this->order->loadMissing('cliente.user');
                if ($this->order->cliente && $this->order->cliente->user && $this->order->cliente->user->email) {
                    Mail::to($this->order->cliente->user->email)->send(new CancelledOrderMail(
                        $this->order,
                        'Cancelado por el restaurante.'
                    ));
                }
            } catch (\Exception $e) {
                Log::error('Error enviando email de cancelación: ' . $e->getMessage());
            }

            $this->dispatch('refresh-badges');
            $this->order->refresh();
        }
    }

    public function confirmCancel()
    {
        $this->dispatch('show-confirmation', [
            'title' => '¿Cancelar Pedido?',
            'message' => '¿Estás seguro de cancelar este pedido?',
            'event' => 'cancelOrderConfirmed',
            'params' => []
        ]);
    }

    public function saveReview()
    {
        if (!auth()->user()->isCliente() || $this->order->estado->nombre_estado !== 'Entregado') {
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
                ->where('id_estado_pedido', '!=', EstadoPedido::where('nombre_estado', 'Entregado')->first()->id_estado_pedido ?? 0) // Show pending/active
                ->latest()
                ->take(10)
                ->get();
        }

        return view('livewire.orders.order-details', [
            'quickAccessOrders' => $quickAccessOrders
        ]);
    }
}
