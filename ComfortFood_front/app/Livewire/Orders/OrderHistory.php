<?php

namespace App\Livewire\Orders;

use App\Models\Pedido;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class OrderHistory extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $date = '';

    // Review properties
    public $showReviewModal = false;
    public $selectedOrderId = null;
    public $rating = 5;
    public $comment = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'date' => ['except' => ''],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function updatedDate()
    {
        $this->resetPage();
    }

    public function openReviewModal($orderId)
    {
        $this->selectedOrderId = $orderId;
        $this->rating = 5;
        $this->comment = '';
        $this->showReviewModal = true;
    }

    public function saveReview()
    {
        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $order = Pedido::find($this->selectedOrderId);
        if (!$order || $order->id_cliente != auth()->user()->cliente->id_cliente) {
            return;
        }

        // Create review
        \App\Models\Resena::create([
            'id_pedido' => $order->id_pedido,
            'id_cliente' => $order->id_cliente,
            'id_restaurante' => $order->id_restaurante,
            // We could optionally link to a menu here, but order-level is fine for now
            'puntuacion' => $this->rating,
            'comentario' => $this->comment,
            'visto' => false, // New review for the restaurant
        ]);

        $this->showReviewModal = false;
        session()->flash('success', '¡Gracias por tu valoración!');
    }

    public function render()
    {
        $user = Auth::user();

        // Mark as seen for client if viewing history
        if ($user->isCliente()) {
            Pedido::where('id_cliente', $user->cliente->id_cliente)
                ->whereHas('estado', function ($q) {
                    $q->whereIn('nombre_estado', ['Completado', 'Cancelado']);
                })
                ->where('visto_completado', false)
                ->update(['visto_completado' => true]);

            // Dispatch event to refresh badge
            $this->dispatch('refresh-badges');
        }

        $query = Pedido::with(['cliente.user', 'restaurante.user', 'estado', 'resena']);

        // Role-based filtering
        if ($user->isCliente()) {
            $query->where('id_cliente', $user->cliente->id_cliente);
        } elseif ($user->isRestaurante()) {
            $query->where('id_restaurante', $user->restaurante->id_restaurante);
        }

        // Search logic
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('id_pedido', 'like', '%' . $this->search . '%')
                    ->orWhereHas('cliente.user', function ($sq) {
                        $sq->where('nombre_completo', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('restaurante.user', function ($sq) {
                        $sq->where('nombre_completo', 'like', '%' . $this->search . '%');
                    });
            });
        }

        // Status filter
        if ($this->status) {
            $query->whereHas('estado', function ($q) {
                $q->where('nombre_estado', $this->status);
            });
        }

        // Date filter
        if ($this->date) {
            $query->whereDate('created_at', $this->date);
        }

        $orders = $query->latest()->paginate(10);

        return view('livewire.orders.order-history', [
            'orders' => $orders,
            'isRestaurant' => $user->isRestaurante(),
        ]);
    }
    protected $listeners = ['cancelOrderConfirmed' => 'cancelOrder'];

    public function confirmCancel($orderId)
    {
        $this->dispatch(
            'show-confirmation',
            title: '¿Cancelar Pedido?',
            message: '¿Estás seguro de cancelar este pedido?',
            confirmAction: 'cancelOrderConfirmed',
            confirmParams: [$orderId],
            confirmText: 'Sí, Cancelar',
            cancelText: 'No, Mantener'
        );
    }

    public function cancelOrder($orderId)
    {
        $order = Pedido::find($orderId);

        if (!$order) {
            return;
        }

        // Solo permitir cancelar si está Pendiente
        if ($order->estado->nombre_estado !== 'Pendiente') {
            session()->flash('error', 'No se puede cancelar el pedido en este estado.');
            return;
        }

        $canceledStatus = \App\Models\EstadoPedido::where('nombre_estado', 'Cancelado')->first();

        if ($canceledStatus) {
            $order->update([
                'id_estado_pedido' => $canceledStatus->id_estado_pedido,
                'visto_completado' => false // Reset to notify client
            ]);

            $this->dispatch('refresh-badges');
            session()->flash('success', 'Pedido cancelado correctamente.');
        }
    }
}
