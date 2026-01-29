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
            // Optional: Check for existing orders before delete or soft delete
            $menu->delete();
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
