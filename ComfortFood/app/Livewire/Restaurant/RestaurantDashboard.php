<?php

namespace App\Livewire\Restaurant;

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
            'En Preparación' => 'En Reparto',
            'En Reparto' => 'Entregado',
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

                if ($nextStatusName === 'En Preparación') {
                    // Deduct Stock
                    foreach ($order->detalles as $detalle) {
                        if ($detalle->menu) {
                            $detalle->menu->decrement('stock', $detalle->cantidad);
                        }
                    }

                    try {
                        $order->loadMissing('cliente.user');
                        if ($order->cliente && $order->cliente->user && $order->cliente->user->email) {
                            Mail::to($order->cliente->user->email)->send(new PreparingOrderMail($order));
                        }
                    } catch (\Exception $e) {
                        Log::error('Error enviando email de preparación: ' . $e->getMessage());
                    }
                }



                if ($nextStatusName === 'En Reparto') {
                    try {
                        $order->loadMissing('cliente.user');
                        if ($order->cliente && $order->cliente->user && $order->cliente->user->email) {
                            Mail::to($order->cliente->user->email)->send(new OutForDeliveryMail($order));
                        }
                    } catch (\Exception $e) {
                        Log::error('Error enviando email de reparto: ' . $e->getMessage());
                    }
                }

                if ($nextStatusName === 'Entregado') {
                    try {
                        $order->loadMissing('cliente.user', 'restaurante.user');
                        if ($order->cliente && $order->cliente->user && $order->cliente->user->email) {
                            Mail::to($order->cliente->user->email)->send(new DeliveredOrderMail($order));
                        }
                    } catch (\Exception $e) {
                        Log::error('Error enviando email de entrega: ' . $e->getMessage());
                    }
                }
            }
        }
    }

    #[On('cancelOrderConfirmed')]
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

            try {
                $order->loadMissing('cliente.user');
                if ($order->cliente && $order->cliente->user && $order->cliente->user->email) {
                    Mail::to($order->cliente->user->email)->send(new CancelledOrderMail(
                        $order,
                        'Cancelado por el restaurante.'
                    ));
                }
            } catch (\Exception $e) {
                Log::error('Error enviando email de cancelación: ' . $e->getMessage());
            }

            $this->dispatch('refresh-badges');
        }
    }

    public function confirmCancel($orderId)
    {
        $this->dispatch('show-confirmation', [
            'title' => '¿Cancelar Pedido?',
            'message' => '¿Estás seguro de cancelar este pedido?',
            'event' => 'cancelOrderConfirmed',
            'params' => [$orderId]
        ]);
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
                'En Reparto' => 3,
                'Entregado' => 4,
                'Completado' => 5,
                'Cancelado' => 6
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
