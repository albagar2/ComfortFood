<?php

namespace App\Livewire\Shared\Notifications;

use App\Models\Pedido;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationToast extends Component
{
    public function getListeners()
    {
        return [
            'refresh-badges' => '$refresh',
        ];
    }

    public function checkNotifications()
    {
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();

        if ($user->isCliente()) {
            $this->checkClientNotifications($user);
        } elseif ($user->isRestaurante()) {
            $this->checkRestaurantNotifications($user);
        }

        $this->dispatch('refresh-badges');
    }

    private function checkClientNotifications($user)
    {
        $newNotifications = Pedido::where('id_cliente', $user->cliente->id_cliente)
            ->whereHas('estado', function ($q) {
                $q->whereIn('nombre_estado', ['Completado', 'Cancelado', 'En Preparación']);
            })
            ->where('visto_completado', false)
            ->with('estado', 'restaurante.user')
            ->get();

        foreach ($newNotifications as $order) {
            $message = "";
            $type = "info";
            $icon = "bell";

            $estado = $order->estado->nombre_estado;
            $restaurante = $order->restaurante->user->nombre_completo;

            if ($estado === 'Completado') {
                $message = "¡Tu pedido #{$order->id_pedido} de {$restaurante} ha sido entregado!";
                $type = "success";
                $icon = "check-circle";
            } elseif ($estado === 'Cancelado') {
                $message = "Tu pedido #{$order->id_pedido} de {$restaurante} ha sido cancelado.";
                $type = "error";
                $icon = "x-circle";
            } elseif ($estado === 'En Preparación') {
                $message = "{$restaurante} está preparando tu pedido #{$order->id_pedido}.";
                $type = "info";
                $icon = "fire";
            }

            if ($message) {
                $this->dispatch('show-toast', [
                    'message' => $message,
                    'type' => $type,
                    'icon' => $icon
                ]);
            }
        }
    }

    private function checkRestaurantNotifications($user)
    {
        // 1. Check for orders that were recently cancelled (automatically or manually)
        $recentlyCancelled = Pedido::where('id_restaurante', $user->restaurante->id_restaurante)
            ->whereHas('estado', function ($q) {
                $q->where('nombre_estado', 'Cancelado');
            })
            ->where('updated_at', '>=', now()->subSeconds(30))
            ->get();

        foreach ($recentlyCancelled as $order) {
            $cacheKey = "restaurant_cancel_alert_{$order->id_pedido}";
            if (!session()->has($cacheKey)) {
                $this->dispatch('show-toast', [
                    'message' => "El pedido #{$order->id_pedido} ha sido cancelado.",
                    'type' => 'error',
                    'icon' => 'x-circle'
                ]);
                session([$cacheKey => true]);
            }
        }

        // 2. Check for pending orders and show periodic/urgent alerts
        $pendingOrders = Pedido::where('id_restaurante', $user->restaurante->id_restaurante)
            ->whereHas('estado', function ($q) {
                $q->where('nombre_estado', 'Pendiente');
            })
            ->whereDate('created_at', now()->today())
            ->get();

        foreach ($pendingOrders as $order) {
            $minutesPending = $order->created_at->diffInMinutes(now());
            $expirationLimit = config('app.order_expiration_minutes', 10);

            // Aviso urgente (2 minutos antes de expirar)
            if ($minutesPending >= ($expirationLimit - 2) && $minutesPending < $expirationLimit) {
                $cacheKeyUrgent = "alert_urgent_{$order->id_pedido}";
                if (!session()->has($cacheKeyUrgent)) {
                    $this->dispatch('show-toast', [
                        'message' => "¡ATENCIÓN! El pedido #{$order->id_pedido} está a punto de expirar.",
                        'type' => 'error',
                        'icon' => 'exclamation-triangle'
                    ]);
                    session([$cacheKeyUrgent => true]);
                }
            }
            // Aviso de nuevo pedido (en el primer minuto)
            elseif ($minutesPending < 1) {
                $cacheKeyNew = "alert_new_{$order->id_pedido}";
                if (!session()->has($cacheKeyNew)) {
                    $this->dispatch('show-toast', [
                        'message' => "¡Nuevo pedido recibido! #{$order->id_pedido}",
                        'type' => 'info',
                        'icon' => 'bell'
                    ]);
                    session([$cacheKeyNew => true]);
                }
            }
        }
    }

    public function render()
    {
        return <<<'HTML'
            <div wire:poll.10s="checkNotifications"></div>
        HTML;
    }
}
