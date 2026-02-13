<?php

namespace App\Livewire\Client;

use App\Models\Menu;
use Livewire\Component;

class MenuShow extends Component
{
    public Menu $menu;
    public $observacion = '';

    public function mount(Menu $menu)
    {
        $this->menu = $menu->load(['restaurante.user', 'restaurante.horarios']);
    }

    #[Livewire\Attributes\Computed]
    public function isRestaurantOpen()
    {
        $now = now();
        $currentDayId = $now->format('N');
        $currentTime = $now->format('H:i:s');

        $schedule = $this->menu->restaurante->horarios->where('id_dia', $currentDayId)->first();

        if (!$schedule || !$schedule->esta_abierto) {
            return false;
        }

        return $currentTime >= $schedule->hora_apertura && $currentTime <= $schedule->hora_cierre;
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
                session()->flash('error', '¡Vaya! No quedan más unidades disponibles de este plato.');
                return;
            }
            $carritoItem->cantidad++;
            // Optional: Update observation? Let's just update it.
            if (!empty($this->observacion)) {
                $carritoItem->observaciones = $this->observacion;
            }
            $carritoItem->save();
        } else {
            \App\Models\Carrito::create([
                'id_cliente' => $cliente->id_cliente,
                'id_menu' => $menuId,
                'id_restaurante' => $menu->id_restaurante,
                'cantidad' => 1,
                'observaciones' => $this->observacion,
            ]);
        }

        $this->dispatch('cart-updated');
        $this->observacion = ''; // Reset
        session()->flash('success', 'Menú añadido al carrito');
    }

    public function render()
    {
        return view('livewire.client.menu-show');
    }
}
