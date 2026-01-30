<?php

namespace App\Livewire;

use App\Models\Menu;
use Livewire\Component;

class ClientDashboard extends Component
{
    public $search = '';
    public $deactivatedIds = [];

    public function moveCardToBottom($menuId)
    {
        if (!in_array($menuId, $this->deactivatedIds)) {
            $this->deactivatedIds[] = $menuId;
        }
    }

    public function enableCard($menuId)
    {
        $this->deactivatedIds = array_values(array_filter($this->deactivatedIds, fn($id) => $id != $menuId));
    }

    public function toggleFavorite($menuId)
    {
        $cliente = auth()->user()->cliente;
        if (!$cliente)
            return;

        $favorito = \App\Models\Favorito::where('id_cliente', $cliente->id_cliente)
            ->where('id_menu', $menuId)
            ->first();

        if ($favorito) {
            $favorito->delete();
        } else {
            \App\Models\Favorito::create([
                'id_cliente' => $cliente->id_cliente,
                'id_menu' => $menuId,
                'id_restaurante' => null
            ]);
        }
    }

    public function render()
    {
        $query = Menu::where('esta_activo', true)
            ->with([
                'restaurante.user',
                'favoritos' => function ($q) {
                    $q->where('id_cliente', auth()->user()->cliente?->id_cliente);
                }
            ])
            ->latest();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nombre_menu', 'like', '%' . $this->search . '%')
                    ->orWhereHas('restaurante.user', function ($sq) {
                        $sq->where('nombre_completo', 'like', '%' . $this->search . '%');
                    });
            });
        }

        return view('livewire.client-dashboard', [
            'menus' => $query->get()->sort(function ($a, $b) {
                $aDeactivated = in_array($a->id_menu, $this->deactivatedIds);
                $bDeactivated = in_array($b->id_menu, $this->deactivatedIds);

                if ($aDeactivated === $bDeactivated)
                    return 0;
                return $aDeactivated ? 1 : -1;
            })
        ]);
    }
}
