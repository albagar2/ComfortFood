<?php

namespace App\Livewire;

use App\Models\Menu;
use Livewire\Component;

class MenuShow extends Component
{
    public Menu $menu;

    public function mount(Menu $menu)
    {
        $this->menu = $menu->load('restaurante.user');
    }

    public function addToCart($menuId)
    {
        $cliente = auth()->user()->cliente;
        if (!$cliente) {
            session()->flash('error', 'Debes iniciar sesión para añadir al carrito');
            return;
        }

        $menu = \App\Models\Menu::find($menuId);
        if (!$menu) {
            session()->flash('error', 'Menú no encontrado');
            return;
        }

        // Check stock
        if ($menu->stock <= 0) {
            session()->flash('error', 'Este menú no tiene stock disponible');
            return;
        }

        // Check if cart has items from a different restaurant
        $existingCart = \App\Models\Carrito::where('id_cliente', $cliente->id_cliente)->first();

        if ($existingCart && $existingCart->id_restaurante != $menu->id_restaurante) {
            session()->flash('error', 'Solo puedes añadir menús de un restaurante a la vez. Vacía tu carrito primero.');
            return;
        }

        // Check if item already in cart
        $carritoItem = \App\Models\Carrito::where('id_cliente', $cliente->id_cliente)
            ->where('id_menu', $menuId)
            ->first();

        if ($carritoItem) {
            // Check if we can add more
            if ($carritoItem->cantidad >= $menu->stock) {
                session()->flash('error', 'No hay suficiente stock disponible');
                return;
            }
            $carritoItem->cantidad++;
            $carritoItem->save();
        } else {
            \App\Models\Carrito::create([
                'id_cliente' => $cliente->id_cliente,
                'id_menu' => $menuId,
                'id_restaurante' => $menu->id_restaurante,
                'cantidad' => 1,
            ]);
        }

        $this->dispatch('cart-updated');
        session()->flash('success', 'Menú añadido al carrito');
    }

    public function render()
    {
        return view('livewire.menu-show');
    }
}
