<?php

namespace App\Livewire\Client;

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

        $cliente->menusFavoritos()->detach($menuId);
        session()->flash('success', 'Menú eliminado de favoritos');
    }

    public function render()
    {
        $cliente = auth()->user()->cliente;
        $favorites = collect();

        if ($cliente) {
            $favorites = $cliente->menusFavoritos()
                ->whereHas('restaurante.user', function ($query) {
                    $query->where('es_activo', true);
                })
                ->with(['restaurante.user'])
                ->latest()
                ->get();
        }

        return view('livewire.client.favorites', [
            'menus' => $favorites
        ]);
    }
}
