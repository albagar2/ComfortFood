<?php

namespace App\Livewire\Menus;

use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MenuManagement extends Component
{
    public function toggleStatus($id)
    {
        $menu = Menu::where('id_menu', $id)->first();
        if ($menu && $menu->id_restaurante == Auth::user()->restaurante->id_restaurante) {
            $menu->esta_activo = !$menu->esta_activo;
            $menu->save();
        }
    }

    public function deleteMenu($id)
    {
        $menu = Menu::where('id_menu', $id)->first();

        if ($menu && $menu->id_restaurante == Auth::user()->restaurante->id_restaurante) {
            $hasActiveOrders = \App\Models\DetallePedido::where('id_menu', $id)
                ->whereHas('pedido.estado', function ($query) {
                    $query->whereNotIn('nombre_estado', ['Completado', 'Cancelado']);
                })->exists();

            if ($hasActiveOrders) {
                session()->flash('error', 'No puedes eliminar este menú mientras haya pedidos en curso. Espera a que todos los pedidos asociados estén completados o cancelados.');
                return;
            }

            $menu->delete();
            session()->flash('success', 'Menú eliminado correctamente.');
        }
    }

    public function render()
    {
        $user = Auth::user();
        if (!$user->isRestaurante()) {
            abort(403);
        }

        $menus = Menu::where('id_restaurante', $user->restaurante->id_restaurante)
            ->latest()
            ->get();

        return view('livewire.menus.menu-management', [
            'menus' => $menus
        ]);
    }
}
