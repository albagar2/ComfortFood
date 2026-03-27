<?php

namespace App\Livewire\Menus;

use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MenuManagement extends Component
{
    public function toggleStatus($id)
    {
        $menu = Menu::where('id_menu', $id)
            ->where('id_restaurante', Auth::user()->restaurante->id_restaurante)
            ->first();

        if (!$menu) {
            return;
        }

        $hasActiveOrders = \App\Models\DetallePedido::where('id_menu', $id)
            ->whereHas('pedido.estado', function ($query) {
                $query->whereNotIn('nombre_estado', ['Completado', 'Cancelado']);
            })
            ->exists();

        if ($hasActiveOrders) {
            session()->flash(
                'error',
                'No puedes cambiar el estado del menú mientras haya pedidos en curso.'
            );
            return;
        }

        $menu->esta_activo = !$menu->esta_activo;
        $menu->save();

        session()->flash(
            'success',
            $menu->esta_activo
            ? 'Menú activado correctamente.'
            : 'Menú marcado como no disponible.'
        );
    }



    public function render()
    {
        $user = Auth::user();
        if (!$user->isRestaurante()) {
            abort(403);
        }

        $menus = Menu::where('id_restaurante', $user->restaurante->id_restaurante)
            ->orderByDesc('esta_activo')
            ->latest()
            ->get();

        return view('livewire.menus.menu-management', [
            'menus' => $menus
        ]);
    }
}
