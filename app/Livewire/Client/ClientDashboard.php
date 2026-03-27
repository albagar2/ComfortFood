<?php

namespace App\Livewire\Client;

use App\Models\Menu;
use Livewire\Component;

class ClientDashboard extends Component
{
    public $search = '';
    public $sort = 'random';
    public $deactivatedIds = [];

    public function moveCardToBottom($menuId)
    {
        if (!in_array($menuId, $this->deactivatedIds)) {
            $this->deactivatedIds[] = $menuId;
            session()->flash('success', 'Menú ocultado temporalmente (movido al final)');
        }
    }

    public function enableCard($menuId)
    {
        $this->deactivatedIds = array_values(array_filter($this->deactivatedIds, fn($id) => $id != $menuId));
        session()->flash('success', 'Menú restaurado correctamente');
    }

    public function toggleFavorite($menuId)
    {
        $cliente = auth()->user()->cliente;
        if (!$cliente)
            return;

        $isFavorite = $cliente->menusFavoritos()->where('menu.id_menu', $menuId)->exists();

        if ($isFavorite) {
            $cliente->menusFavoritos()->detach($menuId);
            session()->flash('success', 'Menú eliminado de favoritos');
        } else {
            $menu = Menu::find($menuId);
            if ($menu) {
                // We use attach to include the id_restaurante in the pivot table if needed
                $cliente->menusFavoritos()->attach($menuId, ['id_restaurante' => $menu->id_restaurante]);
                session()->flash('success', '¡Su menú se ha añadido a favoritos correctamente!');
            }
        }
    }

    public function isFavorite($menuId)
    {
        return auth()->user()->cliente?->menusFavoritos()->where('menu.id_menu', $menuId)->exists();
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
                session()->flash('error', '¡Vaya! No quedan más unidades disponibles de este menú.');
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
        $query = Menu::where('esta_activo', true)
            ->whereHas('restaurante.user', function ($q) {
                $q->where('es_activo', true);
            })
            ->with([
                'restaurante.user',
                'restaurante.horarios',
                'restaurante' => function ($q) {
                    $q->withAvg('resenas', 'puntuacion');
                },
                'favoritos' => function ($q) {
                    $q->where('id_cliente', auth()->user()->cliente?->id_cliente);
                }
            ]);

        // Apply Sorting before search/get for DB optimization
        if ($this->sort === 'price_asc') {
            $query->orderBy('precio', 'asc');
        } elseif ($this->sort === 'price_desc') {
            $query->orderBy('precio', 'desc');
        } else {
            // Default: Random order as requested
            $query->inRandomOrder();
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nombre_menu', 'like', '%' . $this->search . '%')
                    ->orWhereHas('restaurante', function ($rq) {
                        $rq->whereHas('user', function ($uq) {
                            $uq->where('nombre_completo', 'like', '%' . $this->search . '%');
                        });
                    });
            });
        }

        $menus = $query->get()->filter(function ($menu) {
            return $menu->stock > 0 && $menu->restaurante->isOpen();
        })->sort(function ($a, $b) {
            // Keep the 'moved to bottom' logic as the final sorting layer
            $aDeactivated = in_array($a->id_menu, $this->deactivatedIds);
            $bDeactivated = in_array($b->id_menu, $this->deactivatedIds);

            if ($aDeactivated === $bDeactivated)
                return 0;
            return $aDeactivated ? 1 : -1;
        });

        return view('livewire.client.client-dashboard', [
            'menus' => $menus
        ]);
    }
}
