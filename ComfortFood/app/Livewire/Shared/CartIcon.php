<?php

namespace App\Livewire\Shared;

use App\Models\Carrito;
use Livewire\Component;
use Livewire\Attributes\On;

class CartIcon extends Component
{
    public $cartCount = 0;
    public $cartTotal = 0;

    public function mount()
    {
        $this->updateCartData();
    }

    #[On('cart-updated')]
    public function updateCartData()
    {
        $cliente = auth()->user()->cliente;
        if (!$cliente) {
            $this->cartCount = 0;
            $this->cartTotal = 0;
            return;
        }

        $cartItems = Carrito::where('id_cliente', $cliente->id_cliente)
            ->with('menu')
            ->get();

        $this->cartCount = $cartItems->sum('cantidad');
        $this->cartTotal = $cartItems->sum(function ($item) {
            return $item->cantidad * $item->menu->precio;
        });
    }

    public function render()
    {
        return view('livewire.shared.cart-icon');
    }
}
