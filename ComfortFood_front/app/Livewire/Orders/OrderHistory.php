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

    public function render()
    {
        $user = Auth::user();
        $query = Pedido::with(['cliente.user', 'restaurante.user', 'estado']);

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
}
