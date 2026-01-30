<?php

namespace App\Livewire;

use App\Models\Carrito;
use App\Models\Pedido;
use App\Models\DetallePedido;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;

class CartModal extends Component
{
    public $showModal = false;
    public $cartItems = [];
    public $restaurantName = '';
    public $total = 0;

    public function mount()
    {
        $this->loadCart();
    }

    #[On('open-cart-modal')]
    public function openModal()
    {
        $this->showModal = true;
        $this->loadCart();
    }

    #[On('cart-updated')]
    public function loadCart()
    {
        $cliente = auth()->user()->cliente;
        if (!$cliente) {
            $this->cartItems = [];
            $this->total = 0;
            return;
        }

        $this->cartItems = Carrito::where('id_cliente', $cliente->id_cliente)
            ->with(['menu', 'restaurante.user'])
            ->get()
            ->toArray();

        if (count($this->cartItems) > 0) {
            $this->restaurantName = $this->cartItems[0]['restaurante']['user']['nombre_completo'] ?? '';
        }

        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total = collect($this->cartItems)->sum(function ($item) {
            return $item['cantidad'] * $item['menu']['precio'];
        });
    }

    public function increaseQuantity($carritoId)
    {
        $carrito = Carrito::find($carritoId);
        if (!$carrito)
            return;

        // Check stock
        if ($carrito->cantidad >= $carrito->menu->stock) {
            $this->dispatch('show-notification', [
                'type' => 'error',
                'message' => 'No hay suficiente stock disponible'
            ]);
            return;
        }

        $carrito->cantidad++;
        $carrito->save();

        $this->loadCart();
        $this->dispatch('cart-updated');
    }

    public function decreaseQuantity($carritoId)
    {
        $carrito = Carrito::find($carritoId);
        if (!$carrito)
            return;

        if ($carrito->cantidad > 1) {
            $carrito->cantidad--;
            $carrito->save();
        } else {
            $carrito->delete();
        }

        $this->loadCart();
        $this->dispatch('cart-updated');
    }

    public function removeItem($carritoId)
    {
        Carrito::destroy($carritoId);
        $this->loadCart();
        $this->dispatch('cart-updated');
    }

    public function clearCart()
    {
        $cliente = auth()->user()->cliente;
        if (!$cliente)
            return;

        Carrito::where('id_cliente', $cliente->id_cliente)->delete();
        $this->loadCart();
        $this->dispatch('cart-updated');
    }

    public function checkout()
    {
        $cliente = auth()->user()->cliente;
        if (!$cliente)
            return;

        if (count($this->cartItems) === 0) {
            $this->dispatch('show-notification', [
                'type' => 'error',
                'message' => 'El carrito está vacío'
            ]);
            return;
        }

        try {
            DB::beginTransaction();

            // Validate stock for all items
            foreach ($this->cartItems as $item) {
                $menu = \App\Models\Menu::find($item['id_menu']);
                if ($item['cantidad'] > $menu->stock) {
                    throw new \Exception("No hay suficiente stock para {$menu->nombre_menu}");
                }
            }

            // Get estado "Pendiente"
            $estadoPendiente = \App\Models\EstadoPedido::where('nombre_estado', 'Pendiente')->first();
            if (!$estadoPendiente) {
                throw new \Exception('Estado de pedido no encontrado');
            }

            // Create order
            $pedido = Pedido::create([
                'id_cliente' => $cliente->id_cliente,
                'id_restaurante' => $this->cartItems[0]['id_restaurante'],
                'precio_total' => $this->total,
                'id_estado_pedido' => $estadoPendiente->id_estado_pedido,
                'direccion_entrega' => $cliente->direccion ?? 'Sin dirección',
            ]);

            // Create order details
            foreach ($this->cartItems as $item) {
                DetallePedido::create([
                    'id_pedido' => $pedido->id_pedido,
                    'id_menu' => $item['id_menu'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['menu']['precio'],
                ]);
            }

            // Clear cart
            Carrito::where('id_cliente', $cliente->id_cliente)->delete();

            DB::commit();

            $this->showModal = false;
            $this->loadCart();
            $this->dispatch('cart-updated');
            $this->dispatch('show-notification', [
                'type' => 'success',
                'message' => 'Pedido creado exitosamente. Esperando confirmación del restaurante.'
            ]);

            // Redirect to orders
            return redirect()->route('orders.history');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-notification', [
                'type' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.cart-modal');
    }
}
