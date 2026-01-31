<?php

namespace App\Livewire;

use App\Models\Menu;
use App\Models\Favorito;
use Livewire\Component;

class Favorites extends Component
{
    public function toggleFavorite($menuId)
    {
        $cliente = auth()->user()->cliente;
        if (!$cliente)
            return;

        $favorito = Favorito::where('id_cliente', $cliente->id_cliente)
            ->where('id_menu', $menuId)
            ->first();

        if ($favorito) {
            $favorito->delete();
        }
    }

    public function render()
    {
        $cliente = auth()->user()->cliente;
        $favorites = [];

        if ($cliente) {
            $favorites = Favorito::where('id_cliente', $cliente->id_cliente)
                ->whereNotNull('id_menu')
                ->whereHas('menu.restaurante.user', function ($query) {
                    $query->where('es_activo', true);
                })
                ->with(['menu.restaurante.user', 'menu.favoritos'])
                ->latest()
                ->get()
                ->pluck('menu')
                ->filter();
        }

        return view('livewire.favorites', [
            'menus' => $favorites
        ]);
    }
}
