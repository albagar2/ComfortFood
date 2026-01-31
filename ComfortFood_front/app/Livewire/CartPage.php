<?php

namespace App\Livewire;

use App\Models\Carrito;
use App\Models\Menu;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class CartPage extends Component
{
    public $cartItems = [];
    public $total = 0;
    public $restaurantName = '';

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $cliente = auth()->user()->cliente;
        if (!$cliente) {
            $this->cartItems = [];
            $this->total = 0;
            return;
        }

        $this->cartItems = Carrito::where('id_cliente', $cliente->id_cliente)
            ->with(['menu.restaurante.user'])
            ->get()
            ->toArray();

        if (count($this->cartItems) > 0) {
            $this->restaurantName = $this->cartItems[0]['menu']['restaurante']['user']['nombre_completo'] ?? '';
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

        if ($carrito->cantidad >= $carrito->menu->stock) {
            session()->flash('error', 'No hay suficiente stock disponible');
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
        session()->flash('success', 'Menú eliminado del carrito');
    }

    public function clearCart()
    {
        $cliente = auth()->user()->cliente;
        if (!$cliente)
            return;

        Carrito::where('id_cliente', $cliente->id_cliente)->delete();
        $this->loadCart();
        $this->dispatch('cart-updated');
        session()->flash('success', 'Carrito vaciado');
    }

    public function checkout()
    {
        $cliente = auth()->user()->cliente;
        if (!$cliente)
            return;

        if (count($this->cartItems) === 0) {
            session()->flash('error', 'El carrito está vacío');
            return;
        }

        try {
            DB::beginTransaction();

            foreach ($this->cartItems as $item) {
                $menu = Menu::find($item['id_menu']);
                if ($item['cantidad'] > $menu->stock) {
                    throw new \Exception("No hay suficiente stock para {$menu->nombre_menu}");
                }
            }

            $estadoPendiente = \App\Models\EstadoPedido::where('nombre_estado', 'Pendiente')->first();
            if (!$estadoPendiente) {
                throw new \Exception('Estado de pedido no encontrado');
            }

            $pedido = \App\Models\Pedido::create([
                'id_cliente' => $cliente->id_cliente,
                'id_restaurante' => $this->cartItems[0]['menu']['id_restaurante'],
                'precio_total' => $this->total,
                'id_estado_pedido' => $estadoPendiente->id_estado_pedido,
                'direccion_entrega' => $cliente->direccion ?? 'Sin dirección',
            ]);

            foreach ($this->cartItems as $item) {
                \App\Models\DetallePedido::create([
                    'id_pedido' => $pedido->id_pedido,
                    'id_menu' => $item['id_menu'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['menu']['precio'],
                    'observaciones' => $item['observaciones'] ?? null,
                ]);
            }

            Carrito::where('id_cliente', $cliente->id_cliente)->delete();

            DB::commit();

            $this->dispatch('cart-updated');
            session()->flash('success', 'Pedido creado exitosamente. Esperando confirmación del restaurante.');

            return redirect()->route('orders.history');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.cart-page');
    }
}
